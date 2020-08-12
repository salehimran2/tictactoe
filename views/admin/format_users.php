<?php

function formatUsers($users) {
  $s = '<div id="admin-users-container">
         <table>
           <tr>
             <th>
               user
             </th>
             <th>
               permissions
             </th>
             <th>
               deregister
             </th>
           </tr>';

  foreach ($users as $user) {
    $s .= formatUser($user);
  }

  return $s . '</table>
       </div>';
}

function formatUser($user) {
  return '<tr class="admin-user" id="admin-user-' . $user['id'] . '">
     <td> ' . $user['username'] . '</td>
     <td>
       ' . ($user['admin'] ? "admin" : "user") .
    '</td>
    <td><a href="javascript:void(0)">deregister</a></td>
  </tr>';
}

?>
