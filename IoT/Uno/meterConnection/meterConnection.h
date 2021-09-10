#include "Arduino.h"
#include <EDB.h>
#include <EEPROM.h>

//structure for internal storage for uno
struct LogEvent {
  char providerId[10];
  char id[10];        //meter ID
  long cReading;      //meter current reading  	
  long pReading;      //meter reading
} 
logEvent;

//The read and write handlers for using the EEPROM Library
void writer(unsigned long address, byte data)
{
  EEPROM.write(address, data);
}
byte reader(unsigned long address)
{
  return EEPROM.read(address);
}

//EDB object with the appropriate write and read handlers
EDB db(&writer, &reader);

// utility functions
static void printError(EDB_Status err)
{
  Serial.print("ERROR: ");
  switch (err)
  {
    case EDB_OUT_OF_RANGE:
      Serial.println("Recno out of range");
      break;
    case EDB_TABLE_FULL:
      Serial.println("Table full");
      break;
    case EDB_OK:
    default:
      Serial.println("OK");
      break;
  }
}
void countRecords()
{
  Serial.print("Record Count: "); 
  Serial.println(db.count());
}

void selectAll()
{  
  for (int recno = 1; recno <= db.count(); recno++)
  {
    EDB_Status result = db.readRec(recno, EDB_REC logEvent);
    if (result == EDB_OK)
    {
      Serial.print(" ID: "); 
      Serial.print(logEvent.id);
      Serial.print(" Provider ID: "); 
      Serial.print(logEvent.providerId);
      Serial.print(" Current Reading: "); 
      Serial.print(logEvent.cReading);
      Serial.print(" Previous Reading: "); 
      Serial.println(logEvent.pReading);	    
    }
    else printError(result);
  }
}

void recordLimit()
{
  Serial.print("Record Limit: ");
  Serial.println(db.limit());
}

void deleteAll()
{
  Serial.print("Truncating table...");
  db.clear();
  Serial.println("DONE");
}

void createRecords(int num_recs,char id[3][10],char pId[3][10],long ini[])
{
  Serial.print("Creating Records...");
  for (int recno = 1; recno <= num_recs; recno++)
  { 
    strcpy(logEvent.id,id[recno-1]);
    strcpy(logEvent.providerId,pId[recno-1]);
    logEvent.cReading = ini[recno-1];
    logEvent.pReading = ini[recno-1];	
    EDB_Status result = db.appendRec(EDB_REC logEvent);
    if (result != EDB_OK) printError(result);
  }
  Serial.println("DONE");
}

