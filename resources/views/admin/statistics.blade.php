<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
        @vite(['resources/css/pages/admin/statistics.css'])

</head>
<body>
  
<div class="page-header">
  <h1>Statistics</h1>
  <h2 class="subtitle">Përmbledhje e shitjeve sipas kategorisë dhe produktit.</h2>
</div>



  <div class="stats-card">
    <table class="stats-table">
      <thead>
        <tr>
          <th>Kategoria e produktit</th>
          <th>Emri i produktit</th>
          <th>Numri i shitjeve</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="pill">Electronics</span></td>
          <td>2 Slot Toaster - Black</td>
          <td class="num">2197</td>
        </tr>
        <tr>
          <td><span class="pill">Clothing</span></td>
          <td>Adults Plain Cotton T-Shirt (2 Pack)</td>
          <td class="num">56</td>
        </tr>
        <tr>
          <td><span class="pill">Home &amp; Kitchen</span></td>
          <td>6 Piece White Dinner Plate Set</td>
          <td class="num">37</td>
        </tr>
        <tr>
          <td><span class="pill">Sports</span></td>
          <td>Intermediate Size Basketball</td>
          <td class="num">127</td>
        </tr>
      </tbody>
    </table>
  </div>
  
</body>
</html>


