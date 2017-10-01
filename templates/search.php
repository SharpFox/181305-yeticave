<main>

<?=$navigationMenu;?>

  <div class="container">
    <section class="lots">
      <h2>Результаты поиска по запросу «<span><?=$searchInfo?></span>»</h2>
      <ul class="lots__list">
        <?=$lotsItemContent;?>
      </ul>
    </section>
    <?=$paginatorContent?>
  </div>

</main>