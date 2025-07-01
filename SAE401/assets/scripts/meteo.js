
    const apiKey = "0a74300e6a2f7409eea7012b17f6a795"; // Remplace par ta clé API
    const city = "Paris"; // Change la ville selon tes besoins
    const weatherDiv = document.getElementById("weather");

    async function getWeather() {
    try {
    const response = await fetch(
    `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`
    );
    const data = await response.json();

    if (data.cod === 200) {
    weatherDiv.innerHTML = `🌡️ ${data.main.temp}°C - ${data.weather[0].description}`;
} else {
    weatherDiv.innerHTML = "❌ Erreur de chargement";
}
} catch (error) {
    weatherDiv.innerHTML = "❌ Erreur de connexion";
}
}

    getWeather();
