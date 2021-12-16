# Send Sms According to City Temperature

This library us written in PHP. 


## Weather 
Uses Weather Api from https://openweathermap.org/  to access current weather data for a city.

  [ https://openweathermap.org/](https://openweathermap.org/)
  

## Sms
Uses Sms Routee API to send sms.

  [ https://docs.routee.net/docs/](https://docs.routee.net/docs/)  

 ## Logic built in main.php
 
 The below procedure is repeated every 10 minutes for 10 times and then stops.
 
 If the temperature is greater than 20C an SMS message is sent to +30.6911111111 with text "Your name and Temperature more than 20C. <the actual temperature>"
 
 else send an sms is sent to +30.6911111111 with text "Your name and Temperature less than 20C. <the actual temperature>"
 
 where <the actual temperature> the temperature that the weather api returns for Thessaloniki.