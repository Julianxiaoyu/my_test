function surligne(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "";
}

function verifPseudo(champ)
{
   if(champ.value.length < 2 || champ.value.length > 25)
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}

function verifForm(f)

{
      var test = 7;
      document.getElementById("t1").value = test;
}


function Grille() {
   this.Completed = false;
   
   this.G = new Array();
      for(var i=0; i<4; i++)
         this.G[i] = new Array();

      for(var i=0; i<4; i++)
         for(var j=0; j<4; j++)
         this.G[i][j] = 0;
}

$( ".action" ).click(function() {
  document.getElementById("t1").value = 5;
  grille = new Grille();
  grille.Completed = true;
  grille.G[1,2] = document.getElementById("t2").value;
  alert(1);
});

$( ".smallsquare" ).click(function() {
  $(this).fadeToggle();
});

$( ".smallsquare1" ).click(function() {
  $(this).fadeOut();
});

