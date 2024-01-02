(function() {
    document.addEventListener("DOMContentLoaded",()=>{
        let buttons = document.querySelectorAll("a.delete");
        if(buttons){
            buttons.forEach(button=>{
                button.addEventListener("click",function(e) {
                    if ( window.confirm( "¿Seguro de que querés eliminar este atributo?" ) ) {
                        return true;
                    }else{
                        e.preventDefault();
                    }
                })
            });
        }
    })
})();