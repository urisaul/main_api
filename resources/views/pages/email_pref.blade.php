<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Prefrences</title>

    <script>
        const dict = {
            "he": {
                "direction": "rtl",
                "header_sub": "הרשמה לרשימת התפוצה",
                "header_unsub": "הסרה מרשימת התפוצה",
                "email": "כתובת מייל:",
                "subscribe": "הרשמה",
                "unsubscribe": "הסרה",
                "success_msg": "הפעולה בוצעה בהצלחה",
                "fail_msg": "הפעולה נכשלה, נא צור קשר בכתובת support@urisaul.com",
            },
            "en": {
                "direction": "ltr",
                "header_sub": "Subscribe to Parsha weekly qustion sheet:",
                "header_unsub": "Unsubscribe from Parsha weekly qustion sheet:",
                "email": "Email:",
                "subscribe": "Subscibe",
                "unsubscribe": "Unsubscibe",
                "success_msg": "The action was completed successfuly",
                "fail_msg": "An error has occurred please contact us at support@urisaul.com",
            },
        };
        let lang = "en";
    </script>
</head>

<body style="direction: ltr;">

    <div class="container" style="text-align: center;">
        <div id="lang-pref" style="margin-bottom: 15px;">
            <button class="change_lang" data-lang="he">עברית</button>
            <button class="change_lang" data-lang="en">English</button>
        </div>
        <form action="" id="main-form">
            <span id="header"></span>
            <br>
            <label for="email-inp" id="email-inp-label">Email:</label>
            <input type="email" required name="email" id="email-inp">
            <button id="sub-btn"></button>
            <button id="unsub-btn" style="display: none;"></button>
        </form>
        <div id="messages"></div>
    </div>

    <script>
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        let action = params.action || "sub";

        let header_elem = document.querySelector("span#header");
        let main_form = document.querySelector("form#main-form");
        let email_inp_label = document.querySelector("label#email-inp-label");
        let email_inp = document.querySelector("input#email-inp");
        let sub_btn = document.querySelector("button#sub-btn");
        let unsub_btn = document.querySelector("button#unsub-btn");

        header_elem.innerText = dict.en.header_sub
        email_inp_label.innerText = dict.en.email
        sub_btn.innerText = dict.en.subscribe
        unsub_btn.innerText = dict.en.unsubscribe

        if (action === "unsub") {
            email_inp.value = params.user_e;
            sub_btn.style.display = "none";
            unsub_btn.style.display = "inline";
        }

        main_form.addEventListener("submit", (e) => {
            e.preventDefault();
            let email = email_inp.value;

            let body = {
                "user_e": email,
                action
            }

            body = JSON.stringify(body);

            document.querySelector("div#messages").innerHTML = "";

            fetch("{{ url('') }}/api/parsha/email_pref", {
                    method: "post",
                    body,
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(res.message);
                    }
                    return res.json()
                })
                .then(res => {
                    document.querySelector("div#messages").innerHTML = dict[lang].success_msg || "";

                    // toggle action
                    action = action === "sub" ? "unsub" : "sub";

                    // toggle elements
                    sub_btn.style.display = action === "sub" ? "inline" : "none";
                    unsub_btn.style.display = action === "sub" ? "none" : "inline";
                    header_elem.innerHTML = action === "sub" ? dict[lang].header_sub : dict[lang].header_unsub;

                })
                .catch(err => {
                    console.log(err.message);
                    document.querySelector("div#messages").innerHTML = dict[lang].fail_msg;
                })
        });

        function handle_lang_pref(e) {
            // const lang = e.target.dataset.lang;
            lang = e.target.dataset.lang;
            document.body.style.direction = dict[lang].direction;
            header_elem.innerText = dict[lang].header_sub
            email_inp_label.innerText = dict[lang].email
            sub_btn.innerText = dict[lang].subscribe
            unsub_btn.innerText = dict[lang].unsubscribe
        }

        document.querySelectorAll("button.change_lang").forEach(elem => elem.addEventListener("click", (e) => handle_lang_pref(e)));
    </script>
</body>

</html>