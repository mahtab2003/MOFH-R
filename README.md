### What is MOFH-R?
MOFH-R is a hosting account and support management system designed to work with MOFH (MyOwnFreeHost) and GoGetSSL. MOFH-R currently has a limited number of features which are listed below:

[![AppVeyor](https://img.shields.io/badge/Licence-GPL_2.0-orange)](LICENSE)
[![AppVeyor](https://img.shields.io/badge/Version-v0.1-informational)](https://github.com/mahtab2003/MOFH-R/releases/latest)
![AppVeyor](https://img.shields.io/badge/Build-Passed-brightgreen)
![AppVeyor](https://img.shields.io/badge/Interface-AdminLTE-lightgreen)
![AppVeyor](https://img.shields.io/badge/Development-Continued-lightgreen)
![AppVeyor](https://img.shields.io/badge/Dependencies-PHP,_MySQL,_cUrl-red)

### Features
- User Management
 - Role Management
- Theme Management
 - Template Management
- Support Management
- Administrative Access
- Integration With:
	- MOFH (MyOwnFreeHost)
	- Google reCAPTCHA 
	- hCaptcha
	- GoGetSSL
	- SMTP
- Update Manager
- Multi-lingual

### Requirements
Your server needs to meet the following minimum requirements to run MOFH-R:
- PHP v5.6 or above (PHP 8 not supported yet).
- MySQL v5.7 or above.
- A valid, trusted SSL certificate.

### Installation 
The installation of MOFH-R is much easier than you think!
- Download the MOFH-R installation files [here](https://github.com/mahtab2003/MOFH-R/releases/latest). Alternatively, if you want the latest development version you can get it [here](https://github.com/mahtab2003/MOFH-R/archive/refs/heads/master.zip).
- Extract the file and upload the contents to your web hosting account. 
- Create a new database for MOFH-R.
- Go to ```https://{your.domain}/{directory}/i/``` and click on the 'Get Started' button.
- Set your website's ```Base URL```, ```Cookie Prefix```, ```Encrtption Key```, enable ```CSRF Protection``` and hit the 'Next Step' button.
- Enter your database credentials and hit 'Next Step' button.
- Register an admin account and login to your admin panel. 
- Replace the logo and favicon located in ```assets/default/img/``` with your own.
- Setup SMTP (see below for some services you can use).
- All done! 

### SMTP
Here are some widely used SMTP services. They have free plans with some limitations, most importantly though, they are compatible with MOFH-R.
- [Mailgun](https://www.mailgun.com/). 
> **Note**  
> Mailgun seems to offer only a trial plan for a month, and without adding a credit card you are only authorized to send emails to 5 recipients. Therefore, you may want to choose another service.
- [Mailjet](https://mailjet.com/).
- [SendGrid](https://sendgrid.com/free/).

### Help
If you require assistance, please proceed to [our forum](https://nxvim.freeflarum.com/), where you can find the answers to many questions and also ask your own.  
You can also [open an issue here](https://github.com/mahtab2003/MOFH-R/issues/new) if you have discovered a bug or have an issue, although the forum is still the preferred way, especially for feature requests. In any way, please ensure your topic has not been previously discussed, and if it has contribute to that discussion instead of making a new one when you can.

### Like MOFH-R?
If you like project MOFH-R please donate [here](https://xera.eu.org/DONATE.md).

### Copyright
This build is created and maintained by [Mehtab Hassan](https://github.com/mahtab2003). Code released under [the GPL-2.0 license](LICENSE).
