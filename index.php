<?php

$countryRules = [
  "KE" => ["name"=>"Kenya", "code"=>"254", "prefixes"=>["7","1"], "length"=>9],
  "UG" => ["name"=>"Uganda", "code"=>"256", "prefixes"=>["7"], "length"=>9],
  "TZ" => ["name"=>"Tanzania", "code"=>"255", "prefixes"=>["6","7"], "length"=>9],
  "NG" => ["name"=>"Nigeria", "code"=>"234", "prefixes"=>["7","8","9"], "length"=>10],
  "US" => ["name"=>"USA / Canada", "code"=>"1", "prefixes"=>["2","3","4","5","6","7","8","9"], "length"=>10],
  "UK" => ["name"=>"United Kingdom", "code"=>"44", "prefixes"=>["7"], "length"=>10],
  "IN" => ["name"=>"India", "code"=>"91", "prefixes"=>["6","7","8","9"], "length"=>10],
  "ZA" => ["name"=>"South Africa", "code"=>"27", "prefixes"=>["6","7","8"], "length"=>9],
  "AE" => ["name"=>"UAE", "code"=>"971", "prefixes"=>["50","52","54","55","56","58"], "length"=>9],
];

$numbers = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $countryKey = $_POST["country"];
    $total = min((int)$_POST["total"], 10000);

    $rule = $countryRules[$countryKey];
    $generated = [];

    while (count($generated) < $total) {
        $prefix = $rule["prefixes"][array_rand($rule["prefixes"])];
        $remaining = $rule["length"] - strlen($prefix);
        $rest = str_pad(rand(0, pow(10,$remaining)-1), $remaining, "0", STR_PAD_LEFT);
        $number = $prefix . $rest;
        $generated[$number] = true; // unique
    }

    foreach (array_keys($generated) as $num) {
        $numbers[] = "+" . $rule["code"] . " " . $num;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Professional Phone Number Generator</title>

<style>
body{
  background:linear-gradient(135deg,#0a1f44,#020617);
  font-family:Segoe UI,system-ui;
  margin:0;padding:20px;color:#fff;
  display:flex;justify-content:center;align-items:center;
}
.container{
  max-width:520px;width:100%;
  background:rgba(255,255,255,.08);
  backdrop-filter:blur(14px);
  border-radius:18px;
  padding:28px;
  box-shadow:0 20px 40px rgba(0,0,0,.4);
}
h1{text-align:center;margin-bottom:20px}
label{display:block;margin-top:12px;font-size:.9rem}
select,input,button{
  width:100%;padding:12px;margin-top:6px;
  border-radius:10px;border:none;font-size:1rem;
}
button{
  margin-top:20px;
  background:linear-gradient(135deg,#1e90ff,#4facfe);
  color:white;font-weight:600;cursor:pointer;
}
button:hover{opacity:.9}
.output{margin-top:25px}
.output-header{
  display:flex;justify-content:space-between;align-items:center
}
.copy{
  background:#fff;color:#0a1f44;
  border-radius:20px;padding:6px 14px;
  font-size:.85rem;cursor:pointer
}
.list{
  background:#fff;color:#000;
  border-radius:14px;padding:15px;
  max-height:320px;overflow:auto;
  font-family:monospace;font-size:.9rem
}
.list span{
  display:block;padding:4px 0;
  border-bottom:1px dashed #ddd
}
</style>
</head>

<body>

<div class="container">
<h1>📱 Mobile Number Generator</h1>

<form method="POST">
<label>Country</label>
<select name="country" required>
<?php foreach($countryRules as $k=>$c): ?>
  <option value="<?=$k?>"><?=$c['name']?> (+<?=$c['code']?>)</option>
<?php endforeach; ?>
</select>

<label>Total Numbers (max 10,000)</label>
<input type="number" name="total" min="1" max="10000" required>

<button>Generate Numbers</button>
</form>

<?php if($numbers): ?>
<div class="output">
<div class="output-header">
  <strong><?=count($numbers)?> Unique Numbers</strong>
  <span class="copy" onclick="copyAll()">Copy</span>
</div>

<div class="list" id="nums">
<?php foreach($numbers as $n): ?>
  <span><?=$n?></span>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</div>

<script>
function copyAll(){
  const text=[...document.querySelectorAll('#nums span')]
    .map(e=>e.innerText).join("\n");
  navigator.clipboard.writeText(text);
  alert("Copied successfully ✅");
}
</script>

</body>
</html>
