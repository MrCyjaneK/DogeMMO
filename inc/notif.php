<a style="display:none" class="bc-dl-button" onclick="alert(document.getElementById('notification_notif_php').innerText)" id="notification_notif_php">
  This is a test notification
</a>
<script>
	notif_http = new XMLHttpRequest();
	const notif_url=document.location.protocol+"//"+window.location.host+'/ajax/notif.php';
	notif_http.withCredentials = true;
	a = setInterval(() => {
		notif_http.open("GET", notif_url);
		notif_http.send();
	},1000);
	notif_http.onreadystatechange = (e) => {
		if (notif_http.readyState == 4 && notif_http.status == 200) {
			if (notif_http.responseText == 'cancel') {
				clearInterval(a);
				return;
			}
			if (notif_http.responseText != 'false') {
				if (notif_http.responseText.length < 40) {
					document.getElementById('notification_notif_php').innerHTML = notif_http.responseText;
					document.getElementById("notification_notif_php").style.display = "block";
				} else {
					document.getElementById("notification_notif_php").style.display = "none";
					alert(notif_http.responseText);
				}
			} else {
				document.getElementById("notification_notif_php").style.display = "none";
			}
		}
	}
</script>
