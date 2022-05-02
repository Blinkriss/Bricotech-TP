        //Initialisation
        animate();
        window.onscroll = function() {
            animate();
        }
        
        
        function animate(){
            const objects = document.getElementsByClassName("animated");
            Actualiseranimated();
            
        function getvh(vhequvalentinpx) {
        // On verifie si la fonction vh() de getvh() est prête
        if (document.getElementById("vh_referent") == null) {
        //On la crée
        createdivreference();
        }
        //Puis on tente de lancer l'operation ! Et on return le resulat
        return vh(vhequvalentinpx);


        function vh(numberofvh) {
        //Cette fonction converti les vh en px
        vh_referent.style.display = "block";
        let onevhheightinpx = vh_referent.offsetHeight;
        vh_referent.style.display = "none";
        let px = onevhheightinpx * numberofvh;
        return px;
        }

        function createdivreference() {
        //Cette fonction crée les elements necessaire à vh()
        const vh_referent = document.createElement("div");
        vh_referent.id = "vh_referent";
        vh_referent.style.display = "none";
        vh_referent.style.height = "1vh";
        vh_referent.style.position = "absolute";
        document.body.appendChild(vh_referent);
        return;
        }
        }

        function Actualiseranimated(){
        //On gere les animateds, à l'actualisation de la page et au scroll
        Array.from(objects).map((item) => {
        var topheight = item.offsetTop;

        //On recupere la position de l'objet dans la page (equivalent à .offset().top en jquery)
        while (item == item.offsetParent) {
        topheight += item.offsetTop;
        }
        //90 ici est la partie de l'ecran au dessus de laquelle l'animated est joué, donc quand l'element rendre dans + de 10% de la hauteur de l'ecran.
        //Si tu souhaite que l'element soit completement dans l'ecran, remple getvh(90) par : item.offsetHeight
        if (window.scrollY > (topheight - getvh(90))) {
        //On defini que l'animated est en cours.
        item.classList.remove("animated");
        item.classList.add("animated-end");
        }
        });
        }
            return;
        }