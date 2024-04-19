// This Api is used to fetch the top 100 games in the last 2 weeks.
const apiUrl = "https://steamspy.com/api.php?request=top100in2weeks";

// USD to CAD exchange rate
const usdToCadRate = 1.37;

// entry point for the function to fetch the game data
function fetchGameRank() {
  return fetch(apiUrl)
    .then((response) => response.json())
    .then((data) => {
      processResults(data);
    });
}

// function to process the game data and add it to the table
function processResults(games) {
  const gamesArray = Object.values(games);
  gamesArray.sort((a, b) => b.average_forever - a.average_forever);
  let rank = 1;
  for (let gameId in gamesArray) {
    const game = gamesArray[gameId];
    addToTable(game, rank);
    rank++;
  }
}

function addToTable(game, rank) {
  const table = document.querySelector(".game-list-table tbody");
  const row = document.createElement("tr");
  const numberTd = document.createElement("td");
  const imageTd = document.createElement("td");
  const nameTd = document.createElement("td");
  const developerTd = document.createElement("td");
  const positiveTd = document.createElement("td");
  const negativeTd = document.createElement("td");
  const priceTd = document.createElement("td");

  const imgLinkTag = document.createElement("a");
  const imgTag = document.createElement("img");
  imgLinkTag.href = `https://store.steampowered.com/app/${game.appid}`;
  imgTag.src = `https://cdn.cloudflare.steamstatic.com/steam/apps/${game.appid}/capsule_184x69.jpg`;
  imgTag.className = "game-image";
  imgLinkTag.appendChild(imgTag);
  imageTd.appendChild(imgLinkTag);

  const nameLinkTag = document.createElement("a");
  nameLinkTag.href = `https://store.steampowered.com/app/${game.appid}`;
  nameLinkTag.innerHTML = game.name;
  nameTd.appendChild(nameLinkTag);

  numberTd.innerHTML = rank;
  developerTd.innerHTML = game.developer;
  positiveTd.innerHTML = game.positive;
  negativeTd.innerHTML = game.negative;
  priceTd.innerHTML = convertUsCentsToCadDollars(game.initialprice);

  row.appendChild(numberTd);
  row.appendChild(imageTd);
  row.appendChild(nameTd);
  row.appendChild(developerTd);
  row.appendChild(positiveTd);
  row.appendChild(negativeTd);
  row.appendChild(priceTd);
  table.appendChild(row);
}

function convertUsCentsToCadDollars(usCents) {
  const usDollars = usCents / 100;
  const cadDollars = usDollars * usdToCadRate;

  let price = cadDollars.toFixed(2);
  if (price == "0.00") {
    price = "Free";
  } else {
    price = "$" + price;
  }

  return price;
}

fetchGameRank();
