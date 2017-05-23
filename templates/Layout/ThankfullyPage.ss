<%-- DELETE THIS SECTION --%>
<div style="color: red; border: 1px solid red; border-radius: 3px; padding: 15px; clear: both; margin: 30px;">
    <strong>You should override this layout by copying thankfully/templates/Layout/ThankfullyPage.ss to $ThemeDir/templates/Layouts/ThankfullyPage.ss and making the modifications required to suit your website</strong>
</div>
<%-- ------------------- --%>

$Title
$Content
<% if $ReturnTo %><a href="$ReturnTo">Return to Previous Page</a><% end_if %>
<a href="/">Return Home</a>