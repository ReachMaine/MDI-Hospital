jQuery(document).ready(function(){
      document.onkeydown = function(e) {
        if(e.keyCode === 13) { // The Enter/Return key
          document.activeElement.onclick(e);
        }
      };
});
