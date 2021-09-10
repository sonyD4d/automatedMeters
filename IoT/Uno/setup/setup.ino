#include "Arduino.h"
#include <EDB.h>
#include <EEPROM.h>
#include "meterConnection.h"
/*------------------------------------------------------------------*/

#define TABLE_SIZE 1024 // Arduino 328
#define RECORDS_TO_CREATE 10

/*------------------------------------------------------------------*/

void setup()
{
  Serial.begin(9600);
  Serial.println("Meter Setup");
  Serial.println();

  Serial.print("Creating database...");
  db.create(0, TABLE_SIZE, (unsigned int)sizeof(logEvent));
  Serial.println("DONE");
  recordLimit();
  ini();
}

/*------------------------------------------------------------------*/

void loop()
{
}

/*------------------------------------------------------------------*/

void ini(){
  //Serial.println("Enter No of meters:");
  char id[3][10]= {"ELE001","WTR001","GAS001"};
  char pid[3][10]= {"BESCOM06","WTRK06","GASL01"};
  long initialR[3]={2565,256,789};
  /*while(!Serial.available()){
      int n = Serial.read();
      for(int i=0;i<n;i++){
          Serial.println("Enter meter id:");
          String s = Serial.readString();
          Serial.println("Meter id:");
          Serial.println(s);
          //delay(4000);
          for(int j=0;j<sizeof(s);j++) 
          { 
              id[i][j]=s[j]; 
          } 
        }
    }*/
  createRecords(3,id,pid,initialR);
  countRecords();
  selectAll();
 }
