document.addEventListener("DOMContentLoaded", function () {
  const ratingSelect = document.getElementById("rating-filter");
  const serviceSelect = document.getElementById("service-filter");

  function updateURL() {
    const params = new URLSearchParams(window.location.search);
    params.set("rating", ratingSelect.value);
    params.set("service", serviceSelect.value);
    params.set("page", 1);
    window.location.href = "?" + params.toString();
  }

  if (ratingSelect) ratingSelect.addEventListener("change", updateURL);
  if (serviceSelect) serviceSelect.addEventListener("change", updateURL);

  const paginationLinks = document.querySelectorAll(".pagination .page-link");
  paginationLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const href = this.getAttribute("href");
      const scrollY = window.scrollY;
      window.history.pushState({}, "", href);
      window.location.reload();
    });
  });

  const reviewForm = document.querySelector(".review-form");
  const submitBtn = document.querySelector(".btn-submit");

  if (typeof window.openAuthModal !== "function") {
    window.openAuthModal = function () {
      var loginModal = document.getElementById("loginModal");
      if (loginModal) {
        loginModal.style.display = "block";
      }
    };
  }

  if (reviewForm) {
    reviewForm.addEventListener("submit", function (e) {
      if (typeof window.isLoggedIn !== "undefined" && !window.isLoggedIn) {
        e.preventDefault();
        if (typeof openAuthModal === "function") {
          openAuthModal();
        } else {
          alert(
            "Пожалуйста, войдите или зарегистрируйтесь, чтобы оставить отзыв."
          );
        }
      }
    });
  }
});
