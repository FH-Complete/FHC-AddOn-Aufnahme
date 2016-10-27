<section id="cookie-container" style="display: block;">
	<div class="container-fluid">
		<p><?php echo $this->getPhrase("Aufnahme/CookieWarning", $sprache, $this->config->item('root_oe')); ?></p>
		<a class="btn" href="javascript:confirmCookies();" id="cookie-disclaimer">Ok</a>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		if(getCookie("confirmCookies") === "true")
		{
			$("#cookie-container").hide();
		}
	});
	
	function confirmCookies()
	{
		document.cookie = "confirmCookies=true";
		$("#cookie-container").hide();
	}
	
	function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}
</script>
