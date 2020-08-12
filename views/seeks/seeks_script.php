<?php

echo <<<JS
    <script>
      "use strict";

      (function () {
        function formatSeeks(seeks, userId, admin) {
          var s = [
            '<table>' +
            '<tr>' +
              '<th>' +
                'user' +
              '</th>' +
              '<th>' +
                'created' +
              '</th>' +
              '<th>' +
                'action' +
              '</th>' +
            '</tr>'
          ];
        
          for (var i = 0; i < seeks.length; i++) {
            s.push(formatSeek(seeks[i], userId, admin));
          }
        
          return s.join("") + '</table>';
        }
        
        function formatSeek(seek, userId, admin) {
          var s = ['<tr class="ttt-seek" id="ttt-seek-' + seek.id + '">' +
             '<td> ' + seek.username + '</td>' +
             '<td>' + seek.timestamp + '</td>'];
        
          if (parseInt(seek.user_id) === userId) {
            s.push('<td><a href="javascript:void(0)">remove</a></td>');
          }
          else {
            s.push('<td><a href="javascript:void(0)">join</a>');
        
            if (admin) {
              s.push(' [admin <a href="javascript:void(0)">remove</a>]');
            }
        
            s.push('</td>');
          }
        
          return s.join("") + '</tr>';
        }

        function handleSeekRequest(responseText) {
          var data = JSON.parse(responseText);
          seeksContainer.innerHTML = 
            formatSeeks(data.seeks, data.userId, data.admin);
          prepareSeekNodes();
        }

        function handleSeekRequestFailure(responseText) {

          // TODO
          console.log(responseText);
        }

        function prepareSeekNode(node) {
          node.addEventListener("click", function (e) {
            var self = this;
            var id = e.target.parentNode.parentNode.id.split("-");
            id = id[id.length-1];
            if (id === "" || id === undefined) { return false; }
            
            if (e.target.innerText.indexOf("remove") >= 0) { // TODO brittle
              var removeSeekRequest = ajax(
                'index.php?page=removeseek', 
                function (responseText) { 
                  self.parentNode.removeChild(self);
                },
                function (responseText) { 
                  // TODO show error
                  console.log(responseText);
                }
              );
              removeSeekRequest.send("id=" + id);
            }
            else if (e.target.innerText.indexOf("join") >= 0) { // TODO brittle
              var joinSeekRequest = ajax(
                'index.php?page=joinseek', 
                function (responseText) { 
                  self.parentNode.removeChild(self);
                },
                function (responseText) { 
                  // TODO show error
                  console.log(responseText);
                }
              );
              joinSeekRequest.send("id=" + id);
            }
          });
        
        }

        function prepareSeekNodes() {  
          var seeks = document.getElementsByClassName("ttt-seek");
        
          for (var i = 0; i < seeks.length; i++) {
            prepareSeekNode(seeks[i]);
          }
        }

        var seeksContainer = document.getElementById("ttt-seeks-container")
        var newSeekBtn = document.getElementById("ttt-new-seek-btn");
        var refreshSeeksBtn = document.getElementById("ttt-refresh-seeks-btn");
        var seeksContainer = document.getElementById("ttt-seeks-container");

        newSeekBtn.addEventListener("click", function () {
          var newSeekRequest = ajax(
            'index.php?page=newseek', 
            handleSeekRequest, 
            handleSeekRequestFailure
          );
          newSeekRequest.send();
        });

        refreshSeeksBtn.addEventListener("click", function () {
          var newSeekRequest = ajax(
            'index.php?page=getseeks', 
            handleSeekRequest, 
            handleSeekRequestFailure
          );
          newSeekRequest.send();
        });

        var newSeekRequest = ajax(
          'index.php?page=getseeks', 
          handleSeekRequest, 
          handleSeekRequestFailure
        );
        newSeekRequest.send();
      })();

    </script>

JS;

?>
