    <script>
      "use strict";

      (function () {

        function onSuccess(responseText, boardElem, squareElem) {
          var responseData = JSON.parse(responseText);
        
          if (responseData.errors.length) {
            // TODO handle errors
          }
          else {
            switchSides(responseData.gameId, boardElem, squareElem);


            if (responseData.result) {
              endGame(boardElem, responseData.result, responseData.endTime);
              drawLine(boardElem.children[0].children[0]);
            }
          }
        }

        function drawLine(gridElem) {
          var winningSquares = findWinningSquares(gridElem);

          if (winningSquares && winningSquares.length === 3) {
            var canvas = document.createElement("canvas");
            document.body.appendChild(canvas);
            var gridRect = gridElem.getBoundingClientRect();
            var squareElem = gridElem.children[0].children[0];
            var squareRect = squareElem.getBoundingClientRect();
            var topLeftSquare = {
              x: squareRect.left + window.scrollX,
              y: squareRect.top + window.scrollY,
              width: squareRect.width,
              height: squareRect.height
            };
            var ctx = canvas.getContext("2d");

            canvas.width = gridRect.width;
            canvas.height = gridRect.height;
            canvas.style.position = "absolute";
            canvas.style.left = gridRect.left + window.scrollX + "px";
            canvas.style.top = gridRect.top + window.scrollY + "px";

            ctx.beginPath();
            ctx.lineWidth = 2;
            ctx.moveTo(
              ((winningSquares[0] % 3) * (topLeftSquare.width + 1)) + topLeftSquare.width / 2,
              ((winningSquares[0] / 3 | 0) * (topLeftSquare.height + 1)) + topLeftSquare.height / 2
            );
            ctx.lineTo(
              ((winningSquares[2] % 3) * (topLeftSquare.width + 1)) + topLeftSquare.width / 2,
              ((winningSquares[2] / 3 | 0) * (topLeftSquare.height + 1)) + topLeftSquare.height / 2 
            );
            ctx.stroke();
          }
        }

        function findWinningSquares(grid) {
          var winningPositions = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 4, 8], 
            [2, 4, 6], [0, 3, 6], [1, 4, 7], [2, 5, 8]
          ];
          var xMoves = {};
          var oMoves = {};

          for (var i = 0, counter = 0; i < grid.children.length; i++) {
            for (var j = 0; j < grid.children[i].children.length; j++, counter++) {
              if (grid.children[i].children[j].innerText.toLowerCase() === "x") {
                xMoves[counter] = true;
              }
              else if (grid.children[i].children[j].innerText.toLowerCase() === "o") {
                oMoves[counter] = true;
              }
            }
          }

          for (var i = 0; i < winningPositions.length; i++) {
            var oWon = true;
            var xWon = true;

            for (var j = 0; j < winningPositions[i].length; j++) {
              if (!(winningPositions[i][j] in oMoves)) {
                oWon = false;
              }

              if (!(winningPositions[i][j] in xMoves)) {
                xWon = false;
              }
            }

            if (oWon || xWon) {
              return winningPositions[i];
            }
          } 
        }

        function onFailure(repsonseText) {
          // TODO
        }

        function handleMove(squareElem) {
          var boardElem = squareElem.parentNode.parentNode.parentNode.parentNode;
          var gameId = boardElem.id.split("-")[2];
          var square = squareElem.id.split("-")[3];
          var moveRequest = ajax(
            "index.php?page=move",
            function (responseText) { onSuccess(responseText, boardElem, squareElem); }, 
            onFailure
          );
          moveRequest.send("game_id=" + gameId + "&square=" + square);
        }

        function endGame(boardElem, result, endTime) {
          boardElem.children[1].innerText = "ended: " + endTime;
          boardElem.children[4].innerText = "result: " + result;
        }

        function switchSides(gameId, boardElem, squareElem) {
          var side = squareElem.className.indexOf("movable-x") >= 0 ? "X" : "O";
          boardElem.classList.remove("ttt-board-toplay");
          makeImmovable(boardElem);
          var toPlayElem = document.getElementById("ttt-toplay-" + gameId);
          toPlayElem.innerText = "to play: " + (toPlayElem.innerText.indexOf("X") >= 0 ? "O" : "X");
          squareElem.innerText = side;
        }

        function makeImmovable(elem) {
          if (elem.children.length) {
            for (var i = 0; i < elem.children.length; i++) {
              makeImmovable(elem.children[i]);
            }
          }
          else {
            elem.classList.remove("movable");
            elem.classList.remove("movable-x");
            elem.classList.remove("movable-o");
          }
        }

        var movableSquares = document.getElementsByClassName("movable");

        for (var i = 0; i < movableSquares.length; i++) {
          movableSquares[i].addEventListener("mouseover", function (e) {
            if (e.target.className.indexOf("movable") >= 0) {
              e.target.innerHTML = e.target.className.indexOf("movable-x") >= 0 ? "X" : "O";
            }
          });

          movableSquares[i].addEventListener("mouseout", function (e) {
            if (e.target.className.indexOf("movable") >= 0) {
              e.target.innerHTML = "";
            }
          });

          movableSquares[i].addEventListener("click", function (e) {
            if (e.target.className.indexOf("movable") >= 0) {
              handleMove(this);
            }
          });
        }
      })();

    </script>
