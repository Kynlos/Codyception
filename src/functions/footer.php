<?php

// Footer function
function footer() {
  echo '<footer class="bg-gray-800 text-white py-4">
  <div class="container mx-auto px-6 text-center">
    &copy; ' . date('Y') . ' Codyception. All rights reserved.
  </div>
</footer>';
}

?>
<style>
    footer {
  position: fixed;
  bottom: 0;
  width: 100%;
}
</style>