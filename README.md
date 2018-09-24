# Dynamic CSS Compiler for co-working without subversion access
Dynamic PHP CSS Compiler for automatically gathering default theme styles within the current directory and sub directories

## Instructions

1) Disable site-styles.css under CSS Resources in the Miva CMS

2) Include the following line within your HEAD Tag

<mvt:assign name="l.settings:page__code" value="l.settings:page:code" />
<mvt:comment>
	for alternate CTGY layouts : 'CTGY' prefix
	Example layout : CTGY_ALL , CTGY_SHOPBYFIT
</mvt:comment>
<mvt:if expr="'CTGY' CIN l.settings:page:code">
	<mvt:assign name="l.settings:page__code" value="'CTGY'" />
</mvt:if>
<mvt:if expr="'PROD' CIN l.settings:page:code">
	<mvt:assign name="l.settings:page__code" value="'PROD'" />
</mvt:if>
<mvt:comment>LOADS PAGE SPECIFIC CSS</mvt:comment>
<link href="&mvte:global:theme_path;/style.php?files=&mvt:page__code;" rel="stylesheet">
<mvt:comment>CUSTOM CSS THROUGH MIVA : CSS RESOURCES</mvt:comment>
<mvt:item name="head" param="css_list" />

3) Add styles.php to your theme's css folder

4) Move the following files to the css folder (For Shadows theme)

core/css/utilities/ReadyTheme.woff
ui/css/ShadowsFontPack.woff

5) Remove the following stylesheets using @imports as they will no longer be necessary
site-styles.css
core/css/core-styles.css
core/css/base/_base.css
core/css/components/_components.css
core/css/elements/_elements.css
core/css/objects/_objects.css
core/css/utilities/_utilities.css
extensions/_extensions.css

*Instructions are for Miva's default Shadows theme and may differ according to your theme selection and server set up.
