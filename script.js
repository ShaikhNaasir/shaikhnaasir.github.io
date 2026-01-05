/* ============ JAVASCRIPT FILE (script.js) ============ */

$(document).ready(function () {
  // Smooth scrolling for navigation links
  $(".nav-links a, .cta-button").on("click", function (e) {
    e.preventDefault();
    const target = $(this).attr("href");
    $("html, body").animate(
      {
        scrollTop: $(target).offset().top - 80,
      },
      1000,
      "swing"
    );
  });

  // Fade-in animation on scroll
  function checkVisibility() {
    $(".fade-in").each(function () {
      const elementTop = $(this).offset().top;
      const elementBottom = elementTop + $(this).outerHeight();
      const viewportTop = $(window).scrollTop();
      const viewportBottom = viewportTop + $(window).height();

      if (elementBottom > viewportTop && elementTop < viewportBottom) {
        $(this).addClass("active");
      }
    });
  }

  // Initial check
  checkVisibility();

  // Check on scroll
  $(window).on("scroll", function () {
    checkVisibility();

    // Navbar background on scroll
    if ($(window).scrollTop() > 100) {
      $("nav").css(
        "background",
        "linear-gradient(135deg, rgba(139, 38, 53, 0.98) 0%, rgba(107, 26, 39, 0.98) 100%)"
      );
    } else {
      $("nav").css(
        "background",
        "linear-gradient(135deg, var(--primary-color) 0%, #6B1A27 100%)"
      );
    }
  });

  // Book cards hover animation
  $(".book-card").hover(
    function () {
      $(this).find(".book-icon").animate(
        {
          fontSize: "70px",
        },
        200
      );
    },
    function () {
      $(this).find(".book-icon").animate(
        {
          fontSize: "60px",
        },
        200
      );
    }
  );

  // Upcoming items stagger animation
  $(".upcoming-item").each(function (index) {
    $(this).css({
      animation: `slideInRight 0.6s ease forwards ${index * 0.2}s`,
      opacity: "0",
    });
  });

  // Add slideInRight animation
  $("<style>")
    .prop("type", "text/css")
    .html(
      `
                    @keyframes slideInRight {
                        from {
                            opacity: 0;
                            transform: translateX(50px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                `
    )
    .appendTo("head");

  // Translation cards scale effect
  $(".translation-card").hover(
    function () {
      $(this).css("transform", "scale(1.05) rotate(1deg)");
    },
    function () {
      $(this).css("transform", "scale(1) rotate(0deg)");
    }
  );

  // Hero text animation
  $(".hero-title")
    .css({
      opacity: "0",
      transform: "translateY(-30px)",
    })
    .animate(
      {
        opacity: 1,
      },
      {
        duration: 1000,
        step: function (now) {
          $(this).css("transform", `translateY(${-30 + 30 * now}px)`);
        },
      }
    );

  $(".hero-subtitle")
    .delay(300)
    .css({
      opacity: "0",
      transform: "translateY(-20px)",
    })
    .animate(
      {
        opacity: 1,
      },
      {
        duration: 1000,
        step: function (now) {
          $(this).css("transform", `translateY(${-20 + 20 * now}px)`);
        },
      }
    );

  $(".hero-description")
    .delay(600)
    .css({
      opacity: "0",
      transform: "translateY(-20px)",
    })
    .animate(
      {
        opacity: 1,
      },
      {
        duration: 1000,
        step: function (now) {
          $(this).css("transform", `translateY(${-20 + 20 * now}px)`);
        },
      }
    );

  $(".cta-button")
    .delay(900)
    .css({
      opacity: "0",
      transform: "scale(0.8)",
    })
    .animate(
      {
        opacity: 1,
      },
      {
        duration: 800,
        step: function (now) {
          $(this).css("transform", `scale(${0.8 + 0.2 * now})`);
        },
      }
    );

  // Parallax effect for hero section
  $(window).scroll(function () {
    const scrolled = $(window).scrollTop();
    $(".hero-content").css("transform", `translateY(${scrolled * 0.5}px)`);
  });

  // Interactive pattern animation
  setInterval(function () {
    $(".pattern-overlay").css("opacity", Math.random() * 0.3 + 0.7);
  }, 3000);

  // Logo pulse animation
  setInterval(function () {
    $(".logo")
      .animate(
        {
          textShadow: "3px 3px 8px rgba(212,175,55,0.8)",
        },
        500
      )
      .animate(
        {
          textShadow: "2px 2px 4px rgba(0,0,0,0.3)",
        },
        500
      );
  }, 2000);
});
