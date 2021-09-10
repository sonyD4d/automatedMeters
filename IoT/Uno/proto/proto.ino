#include "Arduino.h"
#include "meterConnection.h"
#include <LiquidCrystal.h>
#include <EDB.h>
#include <EEPROM.h>
#include <string.h>
#include <Thread.h>
#include <ThreadController.h>

/*------------------------------------------------------------------*/
struct LogEvent rec[4];
LiquidCrystal lcd(8, 9, 10, 11, 12, 13);
/*------------------------------------------------------------------*/
/*------------------------------------------------------------------*/
ThreadController controll = ThreadController();

//Thread for pulse
Thread pThread = Thread();
//LCD Thread
Thread dThread = Thread();
//Pulse Simulation threads 
Thread pELEThread = Thread();
Thread pWTRThread = Thread();
Thread pGASThread = Thread();

//Thread callback
void pUpdate(){
  while (Serial.available() > 0) {
    char incoming = Serial.read();
    increment(incoming);
  }
}
int dNo=1;
void dUpdate(){
  updateD(rec[dNo]);
  if(dNo==3){
      dNo=1;
    }
  else{
      dNo++;
    }
}

//pulse simulation
void pELEUpdate(){
    int buttonState1 = digitalRead(A0);
    if(buttonState1==HIGH){
      increment('e');
    }
}
void pWTRUpdate(){
    int buttonState1 = digitalRead(A1);
    if(buttonState1==HIGH){
      increment('w');
    }
}
void pGASUpdate(){
    int buttonState1 = digitalRead(A2);
    if(buttonState1==HIGH){
      increment('g');
    }
}

/*------------------------------------------------------------------*/
void setup()
{
  //LCD contrast pin
  pinMode(5, OUTPUT);
  //pulse reading
  pinMode(A0,INPUT);
  pinMode(A1,INPUT);
  pinMode(A2,INPUT);
  
  Serial.begin(9600);
  analogWrite(5, 60);
  
  lcd.clear();
  lcd.begin(16, 2);
  db.open(0);
  //initilize rec[];
  for (int recno = 1; recno <= db.count(); recno++)
  {
    EDB_Status result = db.readRec(recno, EDB_REC logEvent);
    if (result == EDB_OK)
    {
      strcpy(rec[recno].id, logEvent.id);
      strcpy(rec[recno].providerId, logEvent.providerId);
      rec[recno].cReading = logEvent.cReading;
      rec[recno].pReading = logEvent.pReading;
    }
    else printError(result);
  }
  //INTR monitoring 
  pThread.onRun(pUpdate);
  pThread.setInterval(100);
  //display
  dThread.onRun(dUpdate);
  dThread.setInterval(3000);
  //pulse simulation 
  pELEThread.onRun(pELEUpdate);
  pELEThread.setInterval(2000);
  pWTRThread.onRun(pWTRUpdate);
  pWTRThread.setInterval(2000);
  pGASThread.onRun(pGASUpdate);
  pGASThread.setInterval(6000);
  controll.add(&pThread);
  controll.add(&dThread);
  controll.add(&pELEThread);
  controll.add(&pWTRThread);
  controll.add(&pGASThread);
}

/*------------------------------------------------------------------*/
void loop()
{
  //read value or pulse simulation
  controll.run(); 
}

/*------------------------------------------------------------------*/

// utility functions

void increment(char c) {
  int recNo = 0;
  int i;
  //for ELE001 pulse
  if (c == 'e') {
    recNo = 1;
    updateR(recNo);
  }
  //for WTR001 pulse
  if (c == 'w') {
    recNo = 2;
    updateR(recNo);
  }
  //for GAS001 pulse
  if (c == 'g') {
    recNo = 3;
    updateR(recNo);
  }
  //for average pulse generation
  //for ELE001 
  if (c == '4') {
    recNo = 1;
    for(i=0;i<384;i++) updateR(recNo); 
  }
  //for WTR001 
  if (c == '5') {
    recNo = 2;
    for(i=0;i<431;i++) updateR(recNo); 
  }
  //for GAS001 
  if (c == '6') {
    recNo = 3;
    for(i=0;i<12;i++) updateR(recNo); 
  }
  if (c == 'p') {
    sendJSON();   
  }
}
//updates the value for each pulse 
void updateR(int recno)
{
  strcpy(logEvent.id, rec[recno].id);
  strcpy(logEvent.providerId, rec[recno].providerId);
  logEvent.cReading = (long)(++rec[recno].cReading);
  logEvent.pReading = rec[recno].pReading;
  EDB_Status result = db.updateRec(recno, EDB_REC logEvent);
  if (result != EDB_OK) printError(result);
}

void updateD(LogEvent e) {
  lcd.clear();
  lcd.setCursor(4, 0);
  lcd.print(e.id);
  lcd.setCursor(0, 1);
  lcd.print(e.cReading);
}
void sendJSON() {
  Serial.print("[");
  for (int i = 1; i < 4; i++) {
    Serial.print("\n{\"id\":\"");
    Serial.print(rec[i].id);
    Serial.print("\",\"pReading\":\"");
    Serial.print(rec[i].pReading);
    long thisInt = rec[i].cReading - rec[i].pReading;
    setP(i, rec[i].cReading);
    Serial.print("\",\"thisInt\":\"");
    Serial.print(thisInt);
    Serial.print("\",\"provider\":\"");
    Serial.print(rec[i].providerId);
    if (i < 3) {
      Serial.print("\"},");
    }
    else {
      Serial.print("\"}");
    }
  }
  Serial.println();
  Serial.println("]");

}
void setP(int recno, long reading)
{
  strcpy(logEvent.id, rec[recno].id);
  strcpy(logEvent.providerId, rec[recno].providerId);
  logEvent.cReading = rec[recno].cReading;
  logEvent.pReading = reading;
  rec[recno].pReading = rec[recno].cReading;
  EDB_Status result = db.updateRec(recno, EDB_REC logEvent);
  if (result != EDB_OK) printError(result);
}
