## OPSDAY

Given a start date for an Operation, the OPSDAY utility will inform the user of the current Operation day (Ops Day), the day since the start of the Operation.  There is 
a configuration option to choose the default display: either Ops Day or Count.

The operation day count starts at AA and progresses to AZ, wherein the day will "flip" to BA on the next day, and so on, and so forth.  
When the opday ZZ is reached, the day will flip to AA and continue.

Clicking on the OPDAY count will toggle between the operation day and a count of the number of days since the start of the operation.  Rollover of ZZ to AA 
is at day 676.  For each rollover, the tool will append a red "+" or "-" to the OPDAY.

## Configuration

The only configuration parameter is the optional inclusion of a "OP_TZ" environment variable to specify the Timezone.  In most cases, leaving this parameter
out will be the case as the utility will default to UTC as the timezone.

## Usage

Note: dates are entered in this format:  yyy-mm-dd

To use the utility, enter the URL into your browser.  If this is the first time the utility is run, the utility will have the user enter 
the OP Start date (unless it has been specified in the URL) and an optional OP Name.  Once entered, the utility will return a webpage showing the
current opday.  A cookie will have been created so that the user will not have to re-enter the start date when the utility is refreshed, or used again. 
If cookie usage has been turned off, then the user will again be asked for the start day when the utility is used again.  In this event, it may be desirous 
to specify the start date in the URL.

Config panel settings:

=OP Start Day
The OP Start Day is configured when:
 1) upon initial use
 2) when the user clicks on the "calendar" link on the main page
 3) when the user goes to opsday/start or opsday/reset (see URI/URL below).
 
To specify the start date in the URL, add the start parameter as such:  /opsday?start=2015-01-15. Doing so will prevent the utility from asking
for a start date.  This new URL may be saved as a bookmark.  This is useful if cookies have been turned off.

=OP Name
When configuring the OP Start Day, the user will also be able to add/edit the Operation Name, which is displayed at the top of the main display page.  
If a name is not entered, then the heading on the main page will default to "OPSDAY".  The user may enter a "space" character, in which case the tool will 
not show an OP Name.

=Display Option
User may select either the Operational Day or the Since (difference between OP Start and OP Day) displays.


URI/URL:

opsday/start - reset the start day to the current day.
opsday/reset - reset both the start day and display options.

The Ops start day may be set by setting the start GET attribute: opsday?start=2015-01-15 
Likewise, the optional target day (the OP Day) may be specified:  /opsday?start=2015-01-15&target=2015-07-22
Please make sure that the start day is before the target day.


API
Much like the URI/URL above, going to opsday/api?start=2015-01-15&target=2015-07-22 will return the data in JSON.

API Main Attributes Returned:
status - 200 for Success, 400 for error.
message - Success if successful -or- a somewhat descriptive message, hopefully.
data - data returned for the given information.

API Data Attributes:
day - the op day.
date - lookup date.
start - date of the start of the operation.
since - the number of days between the start and lookup date.

API Example:

URI:       http://localhost/opsday/public/api?start=2015-06-01&target=2015-06-24

The start parameter is required, target parameter is optional.

Response:  {"status":"200","message":"Success","data":{"day":"AX","date":"2015-06-24","start":"2015-06-01","since":23}}


### License

OPSDAY

The MIT License (MIT)

Copyright (c) 2015 George Patton Simcox, email: geo.simcox@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

### Contact

Mr. George P. Simcox
geo.simcox@gmail.com