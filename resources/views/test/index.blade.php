<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

  <?php 
    $data_at = array(120, 90, 135, 130, 120, 140, 135, 125);
    $alpha = 0.9;
    $ft_sebelumnya = $data_at[0];
    $hasil_sum = 0;
  ?>

  <h2>HTML Table</h2>

  <table>
    <tr>
      <th>No.</th>
      <th>Bulan</th>
      <th>At</th>
      <th>Ft</th>
      <th>(At - Ft) / At</th>
    </tr>

    <tr>
      <td>1</td>
      <td>Bulan 1</td>
      <td><?= $data_at[0] ?></td>
      <td><?= $data_at[0] ?></td>
      <td>0</td>
    </tr>

    <?php for ($i=1; $i < count($data_at); $i++) { ?>

    <tr>
      <td><?= $i+1 ?></td>
      <td>Bulan <?= $i+1 ?></td>
      <td><?= $data_at[$i] ?></td>
      <td>
        <!-- rumus nyari Ft -->
        <?php  
          $at_sebelumnya = $data_at[$i-1];
          $ft_sekarang = $ft_sebelumnya + $alpha * ($at_sebelumnya - $ft_sebelumnya);

          echo $ft_sekarang;

          $ft_sebelumnya = $ft_sekarang;
        ?>
      </td>
      <td>
        <!-- rumus nyari (At - Ft) / At -->
        <?php  
          $at_sekarang = $data_at[$i];
          $hasil = abs($at_sekarang - $ft_sekarang) / $at_sekarang;

          echo $hasil;

          $hasil_sum = $hasil_sum + $hasil;
        ?>
      </td>
    </tr>
    

    <?php } ?>

    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td rowspan="5"><?= $hasil_sum ?></td>
    </tr>

  </table>

</body>
</html>
