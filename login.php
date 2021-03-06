<?php
include getcwd() . '/inc/config.php';
if (isset($_POST['username'])) {
    var_dump($_POST);
    include getcwd() . "/inc/db.php";
    $q = $db->prepare("SELECT * FROM `users` WHERE `username`=:username AND `password`=:password");
    $q->bindParam(":username", $_POST['username']);
    $q->bindParam(":password", $_POST['password_enc']);
    $q->execute();
    $q = $q->fetchAll();
    if ($q == []) {
        $e = "Incorrect username or password";
    }
    if (isset($e)) {
        include 'inc/error.php';
        die();
    }

    session_name('st34lm3');
    session_start();
    $_SESSION = [];
    $_SESSION['authid'] = $q[0]['ID'];
    $_SESSION['version'] = 3;
    $_SESSION['authtime'] = time();
    die('<meta http-equiv="Refresh" content="0; URL='.WEB.'/game.php" />');
}
?>
<!DOCTYPE html>
    <head>
        <title>DogeMMO</title>
        <link rel="stylesheet" href="<?= WEB ?>/styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="max-width: 650px;">
        <h1>Login</h1>

        <form>
            <label for="username"><b>Username</b></label><br />
            <input type="text" size="34" id="username" name="username" value="" required><br />
            <label for="password"><b>Password</b></label><br />
            <input type="password" size="34" id="password" name="password" value="" required><br />
            <button onclick="login(); return false">Login</button>
        </form>

        <!---- S C R I P T S ---->
        <script>
            login = (() => {
                username = document.getElementById('username').value;
                password = document.getElementById('password').value;
                password_enc = sha256(sha256(sha256(username)+"|"+sha256(password))+'|'+sha256(sha256(username)+"|"+sha256(password)));
                //console.log("'"+username+"'","'"+password+"'","'"+password_enc+"'");
                data = {
                    username: username,
                    password_enc: password_enc,
                };
                post("<?= WEB ?>/login.php", data)
            })
            // https://stackoverflow.com/a/133997
            function post(path, params, method='post') {
                // The rest of this code assumes you are not using a library.
                // It can be made less wordy if you use one.
                const form = document.createElement('form');
                form.method = method;
                form.action = path;
                for (const key in params) {
                    if (params.hasOwnProperty(key)) {
                        const hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = key;
                        hiddenField.value = params[key];

                        form.appendChild(hiddenField);
                    }
                }
                document.body.appendChild(form);
                form.submit();
            }
            // Stolen from https://geraintluff.github.io/sha256/ :>
var sha256 = function sha256(ascii) {
	function rightRotate(value, amount) {
		return (value>>>amount) | (value<<(32 - amount));
	};

	var mathPow = Math.pow;
	var maxWord = mathPow(2, 32);
	var lengthProperty = 'length'
	var i, j; // Used as a counter across the whole file
	var result = ''

	var words = [];
	var asciiBitLength = ascii[lengthProperty]*8;

	//* caching results is optional - remove/add slash from front of this line to toggle
	// Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
	// (we actually calculate the first 64, but extra values are just ignored)
	var hash = sha256.h = sha256.h || [];
	// Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
	var k = sha256.k = sha256.k || [];
	var primeCounter = k[lengthProperty];
	/*/
	var hash = [], k = [];
	var primeCounter = 0;
	//*/

	var isComposite = {};
	for (var candidate = 2; primeCounter < 64; candidate++) {
		if (!isComposite[candidate]) {
			for (i = 0; i < 313; i += candidate) {
				isComposite[i] = candidate;
			}
			hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
			k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
		}
	}

	ascii += '\x80' // Append Ƈ' bit (plus zero padding)
	while (ascii[lengthProperty]%64 - 56) ascii += '\x00' // More zero padding
	for (i = 0; i < ascii[lengthProperty]; i++) {
		j = ascii.charCodeAt(i);
		if (j>>8) return; // ASCII check: only accept characters in range 0-255
		words[i>>2] |= j << ((3 - i)%4)*8;
	}
	words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
	words[words[lengthProperty]] = (asciiBitLength)

	// process each chunk
	for (j = 0; j < words[lengthProperty];) {
		var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
		var oldHash = hash;
		// This is now the undefinedworking hash", often labelled as variables a...g
		// (we have to truncate as well, otherwise extra entries at the end accumulate
		hash = hash.slice(0, 8);

		for (i = 0; i < 64; i++) {
			var i2 = i + j;
			// Expand the message into 64 words
			// Used below if
			var w15 = w[i - 15], w2 = w[i - 2];

			// Iterate
			var a = hash[0], e = hash[4];
			var temp1 = hash[7]
				+ (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
				+ ((e&hash[5])^((~e)&hash[6])) // ch
				+ k[i]
				// Expand the message schedule if needed
				+ (w[i] = (i < 16) ? w[i] : (
						w[i - 16]
						+ (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
						+ w[i - 7]
						+ (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
					)|0
				);
			// This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
			var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
				+ ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj

			hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
			hash[4] = (hash[4] + temp1)|0;
		}

		for (i = 0; i < 8; i++) {
			hash[i] = (hash[i] + oldHash[i])|0;
		}
	}

	for (i = 0; i < 8; i++) {
		for (j = 3; j + 1; j--) {
			var b = (hash[i]>>(j*8))&255;
			result += ((b < 16) ? 0 : '') + b.toString(16);
		}
	}
	return result;
};
            </script>
        </body>
    </html>
