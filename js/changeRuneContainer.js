
const primaryRuneSelector = document.querySelectorAll(".primaryRuneSelection");
const bigRuneSelectContainer = document.querySelectorAll(".bigRuneSelectContainer");
const smallRuneSelectContainer1 = document.querySelectorAll(".smallRuneSelectContainer1");
const smallRuneSelectContainer2 = document.querySelectorAll(".smallRuneSelectContainer2");
const smallRuneSelectContainer3 = document.querySelectorAll(".smallRuneSelectContainer3");
const secondaryRuneSelector = document.querySelectorAll(".secondaryRuneSelection");
const secondaryRunesContainer = document.querySelectorAll(".secondarySmallRuneSelectContainer");


primaryRuneSelector.forEach(selection => {
    // console.log(selection.value);
    selection.addEventListener("click", changeRuneContainer);
});

secondaryRuneSelector.forEach(selection => {
    selection.addEventListener("click", changeSecondaryRuneContainer)
});


function changeRuneContainer() {

    // console.log(bigRuneSelectContainer);
    // console.log(this.id);

    if ( this.id == "precisionSelection" ) {

        // console.log("precision");
        hideAllRunes("precision");

    } else if ( this.id == "dominationSelection" ) {

        // console.log("domination");
        hideAllRunes("domination");

    } else if ( this.id == "sorcerySelection" ) {

        hideAllRunes("sorcery");

    } else if ( this.id == "resolveSelection" ) {

        hideAllRunes("resolve");

    } else if ( this.id == "inspirationSelection" ) {

        hideAllRunes("inspiration");

    } 

}

function changeSecondaryRuneContainer() {

    if ( this.id == "secondaryprecisionSelection" ) {

        // console.log("precision");
        hideAllSecondaryRunes("precision");

    } else if ( this.id == "secondarydominationSelection" ) {

        // console.log("domination");
        hideAllSecondaryRunes("domination");

    } else if ( this.id == "secondarysorcerySelection" ) {

        hideAllSecondaryRunes("sorcery");

    } else if ( this.id == "secondaryresolveSelection" ) {

        hideAllSecondaryRunes("resolve");

    } else if ( this.id == "secondaryinspirationSelection" ) {

        hideAllSecondaryRunes("inspiration");

    } 

}

function hideAllRunes(p) {
    let displayRuneContainer1 = `.${p}Runes1`;
    let displayRuneContainer2 = `.${p}Runes2`;
    let displayRuneContainer3 = `.${p}Runes3`;
    let displayRuneContainer4 = `.${p}Runes4`;
    // console.log(p);
    let checkFirstRadio1 = `#${p}check`;
    let checkFirstRadio2 = `#${p}check2`;
    let checkFirstRadio3 = `#${p}check3`;
    let checkFirstRadio4 = `#${p}check4`;
    // console.log(pLength);
    bigRuneSelectContainer.forEach(container => {
        container.style.display = "none";
    });
    smallRuneSelectContainer1.forEach(container => {
        container.style.display = "none";
    });
    smallRuneSelectContainer2.forEach(container => {
        container.style.display = "none";
    });
    smallRuneSelectContainer3.forEach(container => {
        container.style.display = "none";
    });

    // Hide Secondary Rune Selection and show the ones that are not used

    secondaryRuneSelector.forEach(selection => {
        if ( selection.id == `secondary${p}Selection` ) {
            selection.style.display = "none";
        } else {
            selection.style.display = "flex";
        }

        if( p == "precision" ){
            document.querySelector("#secondarydominationSelection").click();
        } else{
            document.querySelector("#secondaryprecisionSelection").click();
        }
    });

    document.querySelector(displayRuneContainer1).style.display = "flex";
    document.querySelector(displayRuneContainer2).style.display = "flex";
    document.querySelector(displayRuneContainer3).style.display = "flex";
    document.querySelector(displayRuneContainer4).style.display = "flex";
    document.querySelector(checkFirstRadio1).checked = true;
    document.querySelector(checkFirstRadio2).checked = true;
    document.querySelector(checkFirstRadio3).checked = true;
    document.querySelector(checkFirstRadio4).checked = true;
}

function hideAllSecondaryRunes(p){
    // Determine visible Runes
    let pLength = p.length;
    let displaySecondaryRunes = `.secondary${p}Runes`;
    let checkFirstSecondaryRadio1 = `#secondary${p}check`;
    let checkFirstSecondaryRadio2 = `#secondary${p}check2`;

    // Hide all Runes
    secondaryRunesContainer.forEach(secondaryRunes => {
        secondaryRunes.style.display = "none";
    });

    // Show the ones that are visible
    const visibleSecondaryRunes = document.querySelectorAll(displaySecondaryRunes);
    visibleSecondaryRunes.forEach(element => {
        element.style.display = "flex";
    });

    // Check the radio buttons where it makes sense
    document.querySelector(checkFirstSecondaryRadio1).checked = true;
    document.querySelector(checkFirstSecondaryRadio2).checked = true;

}