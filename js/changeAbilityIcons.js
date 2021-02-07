// Wenn ein Champion gewählt/gewechselt wurde soll eine funktion passieren
const championSelect = document.querySelector("#championSelect");
championSelect.addEventListener("change", changeAbilityIcons);

// Funktion zum wechseln der Fähigkeits Icons
function changeAbilityIcons() {
    
    // console.log(this.value);

    //Variabeln definieren
    const abilityContainer1 = document.querySelectorAll(".abilityContainer1 .abilityIcon");
    const abilityContainer2 = document.querySelectorAll(".abilityContainer2 .abilityIcon");
    const abilityContainer3 = document.querySelectorAll(".abilityContainer3 .abilityIcon");
    const abilityContainer4 = document.querySelectorAll(".abilityContainer4 .abilityIcon");

    const abilityValue1 = document.querySelectorAll(".abilityContainer1 .abilityMaxing1");
    const abilityValue2 = document.querySelectorAll(".abilityContainer2 .abilityMaxing2");
    const abilityValue3 = document.querySelectorAll(".abilityContainer3 .abilityMaxing3");
    const abilityValue4 = document.querySelectorAll(".abilityContainer4 .abilityMaxing4");
    // console.log(abilityContainer);

    // let championName;

    // Die richtige json Datei mithilfe des Champion namens fetchen
    fetch(`http://ddragon.leagueoflegends.com/cdn/10.24.1/data/en_US/champion/${this.value}.json`)
    .then(response => response.json())
    .then(data => {
        for(x in data.data){

            let i = 0;

            // rausfinden welche Fähigkeit das ist
            let whichAbility = ""; 
            data.data[x].spells.forEach(spell => {
                // console.log(spell);

                switch (i) {
                    case 0:
                    whichAbility = "Q";
                      break;
                    case 1:
                    whichAbility = "W";
                      break;
                    case 2:
                    whichAbility = "E";
                      break;
                    case 3:
                    whichAbility = "R";
                      break;
                  }

                  // Icons sowie der Value des Inputs werden geändert
                abilityContainer1[i].innerHTML = `<img src="../img/symbols/championAbilitys/${spell.image.full}" alt="Image of the ${whichAbility} Ability">`;
                abilityContainer2[i].innerHTML = `<img src="../img/symbols/championAbilitys/${spell.image.full}" alt="Image of the ${whichAbility} Ability">`;
                abilityContainer3[i].innerHTML = `<img src="../img/symbols/championAbilitys/${spell.image.full}" alt="Image of the ${whichAbility} Ability">`;
                abilityContainer4[i].innerHTML = `<img src="../img/symbols/championAbilitys/${spell.image.full}" alt="Image of the ${whichAbility} Ability">`;
                abilityValue1[i].value = `${spell.image.full}/${whichAbility}`;
                abilityValue2[i].value = `${spell.image.full}/${whichAbility}`;
                abilityValue3[i].value = `${spell.image.full}/${whichAbility}`;
                abilityValue4[i].value = `${spell.image.full}/${whichAbility}`;
                i++;
            });
            // console.log(data.data[x].spells[0].image.full)
        }
    });

}