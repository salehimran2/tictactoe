    <script>
      "use strict";

      (function () {

        function onSuccess(responseText) {
          var responseData = JSON.parse(responseText);
        
          if (responseData.errors.length) {
            var html = [];

            for (var i = 0; i < responseData.errors.length; i++) {
              html.push("<li>" + responseData.errors[i] + "</li>");
            }

            entrywayErrorsElem.innerHTML = html.join("");
          }
          else {
            location.reload(); // TODO
          }
        }

        function onFailure(repsonseText) {
          entrywayErrorsElem.innerHTML = "<p>there was an error processing your request. try again soon.</p>";
        }

        var entrywayErrorsElem = document.getElementById("entryway-errors");

        document.forms[0].addEventListener("submit", function (e) {
          entrywayErrorsElem.innerHTML = "";
          e.preventDefault();
          var data = {};

          for (var i = 0; i < this.elements.length - 1; i++) {
            var key = this.elements[i].placeholder.replace(/^([a-z]+) ([a-z]+)$/i, function (str, m2, m3) {
              return m3 + m2.substr(0, 1).toUpperCase() + m2.substr(1);
            });
            data[key] = this.elements[i].value;
          }

          ajax(
            "index.php?page=register", onSuccess, onFailure, "POST", "application/json; charset=UTF-8"
          ).send(JSON.stringify(data));
        });

        document.forms[1].addEventListener("submit", function (e) {
          entrywayErrorsElem.innerHTML = "";
          e.preventDefault();
          var data = {};

          for (var i = 0; i < this.elements.length - 1; i++) {
            data[this.elements[i].placeholder] = this.elements[i].value;
          }

          ajax(
            "index.php?page=login", onSuccess, onFailure, "POST", "application/json; charset=UTF-8"
          ).send(JSON.stringify(data));
        });
      })();

    </script>
