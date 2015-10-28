#include <b64.h>
#include <HttpClient.h>
#include <SPI.h>
#include <Ethernet.h>
#include <EthernetClient.h>
#include <Wire.h>
#include <Adafruit_AM2315.h>

byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
// fill in an available IP address on your network here,
// for manual configuration:
IPAddress ip(192, 168, 1, 211);

// fill in your Domain Name Server address here:
IPAddress myDns(8, 8, 8, 8);

// initialize the library instance:
EthernetClient client;

// char server[] = "www.arduino.cc";
IPAddress server(192,168,1,200);
unsigned long lastConnectionTime = 0;             // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 10L * 1000L; // delay between updates, in milliseconds
// the "L" is needed to use long type numbers

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  Ethernet.begin(mac, ip, myDns);
  // print the Ethernet board/shield's IP address:
  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
  Adafruit_AM2315 am2315;
   int setup = 1;
}

void loop() {
  Adafruit_AM2315 am2315;
  // put your main code here, to run repeatedly:
  // sensor types listed below with HARDCODED Id's -- hopefully
  // i will get time to automate detection of devices
 float intCurTemp;
 float intCurHum;
  int intCurCO2;
  int intCurLight;
  int intSensorVal;
  int intSensor;
  int intTempSensor =1;
  int intHumSensor = 2;
  int intCO2Sensor = 3;
  int intLumenSensor = 4;
  
//INIT WEBCLIENTREPORTING OBJECT
//WebClientRepeating webHttp;

  // TEMPERATURE SENSOR UPLOAD
  //intCurTemp = Adafruit_AM2315::readTemperature(void);
  //Adafruit_AM2315 sensorTempHum;
  //boolean booGrabTempHum =sensorTempHum.readTemperatureAndHumidity(intCurTemp, intCurHum);
  //USE DATA ACQUIRED ABOVE FROM SENSOR CALL THAT UPDATES BOTH VARIABLES



  intTempSensor = am2315.readTemperature();
  intSensor = intTempSensor; // when the HTTP req of sensor is pulled from
  intSensorVal = intCurTemp;
 httpRequest(intSensor, intSensorVal);
  // NOW USE DATA FOR HUMID OBTAINED IN PREVIOUS FUNCTION FOR VALUE AND CHANGE TO HUMID SENSOR ID
  intSensor = intHumSensor; // when the HTTP req of sensor is pulled from
  intSensorVal = intCurHum;
httpRequest(intSensor, intSensorVal);


  //CO2 SENSOR UPLOAD
  intCurCO2 = 0;// NEED CODE IMPLEMENTED FOR THIS
  intSensor = intCO2Sensor; // when the HTTP req of sensor is pulled from
  intSensorVal = intCurCO2;
  httpRequest(intSensor, intSensorVal);

  // LIGHT SENSOR UPLOAD
  intCurLight = 0; // NEED CODE XXXX
  intSensor = intLumenSensor; // when the HTTP req of sensor is pulled from
  intSensorVal = intCurLight;
 httpRequest(intSensor, intSensorVal);


//  HUMIDITY SENSOR UPLOAD
  delay(3); // we need 3 seconds min between each read of temp/humid
  intCurHum = am2315.readHumidity();
  intSensor = intHumSensor; // when the HTTP req of sensor is pulled from
  intSensorVal = intCurHum;
 httpRequest(intSensor, intSensorVal);


}

void httpRequest(float intSensor, float intValue) {
  
  // close any connection before send a new request.
  // This will free the socket on the WiFi shield
  client.stop();

  // if there's a successful connection:
  if (client.connect(server, 80)) {
    Serial.println("connecting...");
    // send the HTTP PUT request:
    // client.println("GET /latest.txt HTTP/1.1");
  char strHttpReq[64] = "GET /sensor.php?="; //+ intSensor + "&val?=" + intSensorVal;
  //  strHttpReq = strHttpReq;
    client.println(strHttpReq);
    client.println("Host: SENSORBAY");
    client.println("User-Agent: arduino-ethernet");
    client.println("Connection: close");
    client.println();

    // note the time that the connection was made:
    lastConnectionTime = millis();
  }
  else {
    // if you couldn't make a connection:
    Serial.println("connection failed");
  }
}

