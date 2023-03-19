// MEMORIA
// GPS + Voice Recorder at the flick of a switch

// TinyGPS++
// TMRpcm
// SD
// SPI
// ezButton
// SoftwareSerial

#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <SD.h>
#include<SPI.h>
#include <ezButton.h>
#include <TMRpcm.h>

// Arduino pins for Software Serial
int RXPin = 4;
int TXPin = 3;
const int chipSelect = 10;
int GPSBaud = 9600;

//Audio Prep
char audioFileName[8] = "Aud.wav";
TMRpcm audio;

// Toggle Switch
const int audio_switch = 5;
ezButton toggleSwitch(audio_switch);

// GPS Prep
TinyGPSPlus gps;
SoftwareSerial gpsSerial(TXPin, RXPin);

void setup() {
  
  // Start software serial at GPS baud 9600
  gpsSerial.begin(GPSBaud);
  
  // Serial Monitor:
  Serial.begin(9600);

  // Defining pins for mic
  pinMode(A0, INPUT);
  pinMode(6, OUTPUT);
  
  Serial.print("I");
  delay(2000);
  if (!SD.begin(chipSelect)) {
    
    Serial.println("N");
    while (1);
    
  }
  Serial.println("Y");

  // Connecting audio pin of the arduino to the Sd card to write
  audio.CSPin = 10;
  
}

void loop() {
  // put your main code here, to run repeatedly:

  // Checking if GPS encoding works
  while (gpsSerial.available() > 0) {
    if (gps.encode(gpsSerial.read())) {
      
    }
  }

  // Monitoring the toggle switch pin
  bool state = digitalRead(5);

  // If the switch is on:
  if (state == 0) {

    // If we are getting valid GPS location values:
    if (gps.location.isValid()) {
      
      String gpsLat = String(gps.location.lat(), 6);
      String gpsLong = String(gps.location.lng(), 6);

      // Get the date and time
      int gps_hour = gps.time.hour();
      int gps_minute = gps.time.minute();
      int gps_second = gps.time.second();

      int gps_day = gps.date.day();
      int gps_month = gps.date.month();
      int gps_year = gps.date.year();

      // Open file
      File dataFile = SD.open("GPS.txt", FILE_WRITE);
      
      // if the file is available, write to it:
      if (dataFile) {

        
        dataFile.println(gpsLat);
  
        dataFile.println(gpsLong);
           
        if (gps_hour < 10) dataFile.print(F("0"));
        dataFile.print((gps_hour - 2));
        
        dataFile.print(":");

        if (gps_minute < 10) dataFile.print(F("0"));
        dataFile.print(gps_minute);
        
        dataFile.print(":");
    
        if (gps_second < 10) dataFile.print(F("0"));
        dataFile.print(gps_second);

        dataFile.print(" ");
        dataFile.print(gps_day);
        dataFile.print("/");
        dataFile.print(gps_month);
        dataFile.print("/");
        dataFile.print(gps_year);
    
        dataFile.println();
        dataFile.println();
    
        dataFile.close();
        
      }
      
    } else {
            
      String dataString2 = "0000";
      String dataString3 = "0000";
      String dataString4 = "0000";
  
      File dataFile = SD.open("GPS.txt", FILE_WRITE);
      
      // if the file is available, write to it:
      if (dataFile) {
  
        dataFile.println(dataString2);
  
        dataFile.println(dataString3);
           
        dataFile.println(dataString4);
    
        dataFile.println();
        dataFile.println();
    
        dataFile.close();
        
      }
      
    }

    //Start Audio Recording
    digitalWrite(6, HIGH);
    audio.startRecording(audioFileName, 32000, A0);
    
  }

  // When the switch is on:
  while(state == 0) {
    
    delay(500);
    
    // Monitoring to see if the switch is turned off:
    bool switchedState = digitalRead(5);

    // When the switch is turned off:
    if (switchedState == 1) {

      //Stop Audio Recording
      digitalWrite(6, LOW);
      audio.stopRecording(audioFileName);

      // Back to looping the sketch
      break;
    }
  }

}
