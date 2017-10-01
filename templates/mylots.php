<main>

  <?=$navigationMenu;?>

  <section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
    <?php foreach ($bets as $key => $bet): ?>
      <tr class="rates__item">
        <td class="rates__info">
          <div class="rates__img">
            <img src=<?=$bet['lotsUrl'];?> width="54" height="40" alt=<?=$bet['lotName'];?>>
          </div>
          <div>
            <h3 class="rates__title"><a href="lot.php?lot-id=<?=$bet['lotId'];?>"><?=$bet['lotName'];?></a></h3>
          </div>
        </td>
        <td class="rates__category">
            <?=$bet['category'];?>
        </td>
        <td class="rates__timer">        
          <div class="timer<?=!empty($bet['winnerId']) ? ' timer--win' : ' timer--finishing'?>">
           <?=!empty($bet['winnerId']) ? 'Ставка выиграла' : getHumanTimeUntilRateEnd($bet['endTime'])?>
          </div>
        </td>
        <td class="rates__price">
            <?=number_format($bet['cost'], 0, '.', ' ') . ' р';?>
        </td>
        <td class="rates__time">
          <?=getHumanTimeOfLastRate($bet['createdTime']);?>
        </td>
      </tr>  
      <?php endforeach; ?>    
    </table>
  </section>

</main>