#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "REPLACE_WITH_YOUR_SSID";
const char* password = "**********";

//The URL to the PHP script that saves the data to a database
String serverName = "https://alonesolutions.ca/smarthomeapi/";

// the following variables are unsigned longs because the time, measured in
// milliseconds, will quickly become a bigger number than can be stored in an int.
unsigned long lastTime = 0;
unsigned long timerDelay = 20000;

String MACaddress;

String uniqueid;

void setup() {
  Serial.begin(115200);
  
  //Initialize WiFi Connection
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  
  //Get MAC address of the board, in order to have a unique identifier for it to distinguish from other potential boards
  MACaddress = WiFi.macAddress();
  MACaddress = String(MACaddress);
  
  //Remove colons from MAC address String
  for (int i = 0; i < MACaddress.length(); i++) {
    if (MACaddress[i] != ':') {
      uniqueid.concat(MACaddress[i]);
    }
  }

  Serial.print("UNIQUE ID: ");
  Serial.println(uniqueid);

  Serial.println("Timer set to 20 seconds (timerDelay variable), it will take 20 seconds before publishing the first reading.");
}

void loop() {
  //Send an HTTP POST request every 10 minutes
  if ((millis() - lastTime) > timerDelay) {
    //Check WiFi connection status
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      
      float temperatureExampleData = 24.6
      
      //create a String that will be appended to the Script URL to encode the data in the URL
      String URLData = "?boardid=" + uniqueid + "&description=temperature&value=" + String(temperatureExampleData);
      String serverPath = serverName + URLData;
      
      Serial.print("SERVER PATH: ");
      Serial.println(serverPath);
      // Your Domain name with URL path or IP address with path
      http.begin(serverPath.c_str());

      // Send HTTP GET request with data encoded in URL
      int httpResponseCode = http.GET();
      
      if (httpResponseCode > 0) {
        Serial.print("HTTP Response code: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println(payload);
      }
      else {
        Serial.print("Error code: ");
        Serial.println(httpResponseCode);
      }
      // Free resources
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected");
    }
    lastTime = millis();
  }

}
