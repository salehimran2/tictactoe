<?php

echo <<<JS
    <script>
      "use strict";

      (function () {

        function prepareUserNode(node) {
          node.addEventListener("click", function (e) {
            var self = this;
            var id = e.target.parentNode.parentNode.id.split("-");
            id = id[id.length-1];
            
            if (e.target.innerText.indexOf("deregister") >= 0) { // TODO brittle
              var removeUserRequest = ajax(
                'index.php?page=deregister', 
                function (responseText) { 
                  self.parentNode.removeChild(self);
                },
                function (responseText) { 
                  // TODO show error
                  console.log(responseText);
                }
              );
              removeUserRequest.send("id=" + id);
            }
          });
        }

        function prepareUserNodes() {  
          var seeks = document.getElementsByClassName("admin-user");
        
          for (var i = 0; i < seeks.length; i++) {
            prepareUserNode(seeks[i]);
          }
        }

        prepareUserNodes();
      })();

    </script>
JS

?>
