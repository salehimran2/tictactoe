
      <nav>
        <ul>
          <?=
            "<li>welcome, <a href='" . APP . "index.php?page=profile'>$username</a></li> " .
            ($admin ? '<li><a href="index.php?page=admin">admin</a></li>' : '');
          ?>
          <li><a href="<?= APP . 'index.php' ?>">current games</a></li>
          <li><a href="<?= APP . 'index.php?page=seeks' ?>">seeks</a></li>
          <li><a href="<?= APP . 'index.php?page=logout ' ?>">log out</a></li>
        </ul>
      </nav>
