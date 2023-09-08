console.log("😎");
const cloud = document.querySelectorAll(".gris");

// console.log(cloud);

cloud.forEach((item) => {
  item.addEventListener("click", () => {
    // console.log("true");
    item.remove();
  });
});
