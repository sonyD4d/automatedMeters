#include "Arduino.h"
#include "meterConnection.h"
#include <EDB.h>
#include <EEPROM.h>

/*------------------------------------------------------------------*/

void setup()
{
  Serial.begin(9600);
  Serial.println("Meter Readings:");
  Serial.println();
  db.open(0);
  countRecords();
  selectAll();
}

/*------------------------------------------------------------------*/

void loop()
{
}

/*------------------------------------------------------------------*/
