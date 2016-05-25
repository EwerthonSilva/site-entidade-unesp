DBO: Database Object
Developed by José Eduardo Biasioli
http://www.peixelaranja.com.br

-----------------------------------------------------------------------------------------
- Version 0.9.8 -------------------------------------------------------------------------
-----------------------------------------------------------------------------------------

Changelog

- Version 0.9.8 ------------------------------------------------------------ 11/03/2015 -

Improvements
- Pocket data added to DBO object
- Added several modal functions
- peixelaranja.js now has a lot of JSON utilities
- dboAdminRedirectCode() allows javascript callbacks in redirects.
- dboAdminPostCode() allows javascript callbacks after DBO Admin operations.
- dbo_login() is deprecated, use dboLogin() instead.
- Lots and lots of improvements (seriouslly)

- Version 0.9.7 -------------------------------------------------------------------------

Improvements
- DBO now supports any kind of single Primary Key
- New auto fields "deleted_by", "deleted_on", "deleted_because"
- New switch for the "inativo" field
- Auto Admin UI improvements
- Filters are now available for fields not present in the listing
- CSRF security added
- New functions peixeRequire() and peixeUnrequire() in peixelaranja.js
- Install now creates all necessary folders and SCSS files
- Bug correction in "Cadastros" menu

- Version 0.9.6 -------------------------------------------------------------------------

Improvements
- Versioned to github at https://github.com/damiansb/dbo.git
- Lots of uselesse files removed
- The script now accepts more custom-<files>.php
- new dboData() function shows date names in pt-BR

- Version 0.9.5 -------------------------------------------------------------------------

Improvements
- Updated to Foundation
- New data field: datatime
- New functions support (append/prepend)
- New field overrides
- Redirects
- Modals for modules
- And a lot, lot more changes...

- Version 0.9.5 (forthcoming) ----------------------------------------------------------

LOOOOOTS of stuff to come....

- Version 0.9.4 ------------------------------------------------------------------------

Improvements
- new feature "form_fields" folder inside "dbo/" allow replacement Autoadmin() inputs
- "perfil" module now allows the selection of "pessoa" in update/insert mode
- fixed annoying jQuery misversion in the installer
- default class "pessoa" now has a better constructor
- dbomaker will now generate all classes with better constructors
- Serious improvement in the number of queries autoAdmin() uses to generate lists
- Number of queries done now displayed under the footer (CTRL + A)

- Version 0.9.3 ------------------------------------------------------------------------

Improvements
- jQuery updated to 1.9 (all instances of .live() replaced to .on())
- permissions.sh now sets 777 to necessary folders (run 'sh permissoes.sh' before install)
- new function generatePassword() creates random passwords
- new function loggedUser() returns logged user id more in a more rellyable way
- new function secureUrl() allows on demand hash to open urls, without database need
- fixed icon paths
- new field type "inativo" allows the logical deletion of data, without removing from db
- various bug fixes

- Version 0.9.2 ------------------------------------------------------------------------

Improvements
- Checkbox Support Fully Implemented
- Default Insert Value Bug Fixed
- makeSlug() - new global function: generates a wordpress-like string
- Added the User Box: Click the username on the menu to change the password through ajax
- Added harder restrictions to multi-join fields
- Added the default "pessoa" class

- Version 0.9.1 ------------------------------------------------------------------------

Improvements
- DBO Install folder permission checks fixed
- DBO Install default admin permissions fixed
- DBO "keep actual picture" bug fixed

- Version 0.9 --------------------------------------------------------------------------

Improvements:
- Ajax buttons with JSON response (see ajax-example.php)
- Auto-view Toggle (after insert/update)
- Custom menus (see beta.php)
- Field 'default value' fixed (dbmaker and insertion)
- Notifications tags and boxes (see beta.php)
- Menu separation (Cockpit and Sidebar)
- Module Class automatic creation with smart constructors
- New, improved message wrapper
- New default module icon
- Sidebar display improvement

- Update warnings: 
--- The menu access roles will be lost upon the new menu separation
    They'll have to be done again