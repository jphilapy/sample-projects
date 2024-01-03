# Readme

How to setup google API Stuff

- Download google api client via composer
- Enable Google Calendar API
- Goto google developer console
- Create Google project
- Create service account and download key into .creds folder inside credentials.json src
- set up Oauth consent screen:
    - User Type = external
    - Use ./auth/calendar scope which is "See, edit, share, and permanently delete all the calendars you can access using Google Calendar"
- Link app to key
- Add service account email to the google calendar you wish to access
