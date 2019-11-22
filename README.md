# Logout-OCMS-Plugin
Auto Logout plugin for OctoberCMS

The goal of this plugin is to make it super easy to implement Frontend and Backend auto logout in OcotberCMS, specifically:
- Front end log out on close
- Front end log out after X min inactivity
- Backend log out on close
- Backend log out on X min inactivity

The Plugin should pass the following use cases, both for Frontend and Backend users:
- 1 tab open, close tab, should be logged out
- 2 tabs open to same page, close one tab, should still be logged in, close both tabs, should be logged out
- 1 tab open, cut internet connection, should be logged out
- 1 tab open, reboot machine, should be logged out
