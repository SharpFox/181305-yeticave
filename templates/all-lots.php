<main>

<?=$navigationMenu;?>

  <div class="container">
    <section class="lots">
      <h2>Все лоты в категории «<span><?=$categoryName?></span>»</h2>
      <ul class="lots__list">
        <?=$lotsItemContent;?>
      </ul>
    </section>
    <?=$paginatorContent?>
  </div>
  
</main>