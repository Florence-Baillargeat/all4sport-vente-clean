const inputUrl = document.getElementById("inputUrl");
const imagePreview = document.getElementById("imagePreview");

inputUrl.addEventListener("input", () => {
	imagePreview.src = inputUrl.value;
});