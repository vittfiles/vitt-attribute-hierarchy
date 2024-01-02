(function() {
	'use strict';
    function updateDataInit(){
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const slug = urlParams.get("slug");
        const subSlug = urlParams.get("sub-slug");
        const dir = document.getElementById("url-vittfiles-ahfw")?.value;
        const nonce = document.getElementById("nonce-vittfiles-ahfw")?.value;
        let bot = document.getElementById("update");
        bot.addEventListener("click",e => {
            e.preventDefault();
            let theList = document.querySelectorAll("#the-list .child-attr")
            if(theList){
                let result = [];
                theList.forEach(li => {
                    let base = prev(li);
                    if(base){
                        result.push([li.getAttribute("data-index"),base.getAttribute("data-index")]);
                    }else{
                        result.push([li.getAttribute("data-index"),"0"]);
                    }
                });
                let res = JSON.stringify({result: result, slug: slug, sub_slug: subSlug});
                var formData = new FormData();
                formData.append( "json", res );
                console.log(res);
                
                document.getElementById("loader").classList.remove("none");
                fetch(dir,{
                    method: "POST",
                    headers: {
                        "X-WP-Nonce": nonce,
                        "Content-Type": "application/json"
                    },
                    body: res
                })
                .then(res=>{return res.ok? res.json(): Promise.reject(res)})
                .then(res=>{
                    console.log(res);
                    alert(res.message);
                    document.getElementById("loader").classList.add("none");
                    let baseList = document.querySelectorAll("#the-list .base");
                    if(baseList){
                        baseList.forEach(li => {
                            res.data.forEach(dat => {
                                if(dat.term_id === parseInt(li.getAttribute("data-index"))){
                                    let base = li.querySelector(".posts");
                                    base.innerHTML = dat.count;
                                }
                            });
                        });
                    }
                })
                .catch(err=>{
                    console.log(err);
                    document.getElementById("loader").classList.add("none");
                    alert("Error");
                });
            }
            console.log("    -----   ----- ---- ");
        });
        function prev(element){
            let previous = element.previousElementSibling;
            while (previous) {
            if (previous.classList.contains("base")) {
                return previous;
            }
            previous = previous.previousElementSibling;
            }
            return null;
        }
    }

    function sortListInit(){
        const sortableList = document.getElementById("the-list");
        let draggedItem = null;
        
        sortableList.addEventListener(
            "dragstart",
            (e) => {
                draggedItem = e.target;
                setTimeout(() => {
                    e.target.style.display =
                        "none";
                }, 0);
        });
        
        sortableList.addEventListener(
            "dragend",
            (e) => {/* 
                console.log("dragend  "+ e.clientY); */
                setTimeout(() => {
                    e.target.style.display = "";
                    draggedItem = null;
                }, 0);
        });
        
        sortableList.addEventListener(
            "dragover",
            (e) => {
                e.preventDefault();/* 
                console.log("over  "+ e.clientY); */
                const afterElement =
                    getDragAfterElement(
                        sortableList,
                        e.clientY);
                const currentElement =
                    document.querySelector(
                        ".dragging");
                if (afterElement == null) {
                    sortableList.appendChild(
                        draggedItem
                    );} 
                else {
                    sortableList.insertBefore(
                        draggedItem,
                        afterElement
                    );}
            });
        
        const getDragAfterElement = (
            container, y
        ) => {
            const draggableElements = [
                ...container.querySelectorAll(
                    "tr:not(.dragging)"
                ),];
        /* 
                console.log(draggableElements) */
            return draggableElements.reduce(
                (closest, child) => {
                    const box =
                        child.getBoundingClientRect();
                    const offset =
                        y - box.top - box.height / 2;
                    if (
                        offset < 0 &&
                        offset > closest.offset) {
                        return {
                            offset: offset,
                            element: child,
                        };} 
                    else {
                        return closest;
                    }},
                {
                    offset: Number.NEGATIVE_INFINITY,
                }
            ).element;
        };
    }

    document.addEventListener('DOMContentLoaded',()=>{
        updateDataInit();
        sortListInit();
    })
})();