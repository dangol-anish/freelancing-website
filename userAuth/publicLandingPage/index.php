<?php
include("../../config/helper/persistLogin.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Localized Freelancing Website</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <header>
      <img
        class="logo-image"
        src="../../assets/logo/test.png"
        alt="Logo Picture"
      />
      <nav>
        <a
          class="header-links"
          href="http://localhost/freelancing-website/userAuth/userRegistration/userRegistrationForm.php"
          >Register</a
        >

        <a
          class="header-links"
          id="login-btn"
          href="http://localhost/freelancing-website/userAuth/userLogin/userLoginForm.php"
          >Login</a
        >
      </nav>
    </header>

    <main id="top">
      <section>
        <article class="article-main-text">
          <h1>Localized Freelancing Website</h1>
          <p>
            Connect. Collaborate. Create. From student talents and clients
            <br />
            all over Nepal!
          </p>
          <a
            class="button"
            href="http://localhost/freelancing-website/userAuth/userRegistration/userRegistrationForm.php"
            >Sign Up Now</a
          >
        </article>
        <article>
          <img src="../../assets/logo/freelancing-img.svg" alt="" />
        </article>
      </section>
    </main>
    <hr />
    <h2 class="secondary-main-heading">Features</h2>
    <main class="secondary-main">
      <section class="secondary-section">
        <div class="feature-box">
          <div class="feature-heading">
            <span class="material-symbols-outlined"> chat </span>
            <h3>Communication Module</h3>
          </div>
          <p class="feature-text">
            An easy communication feature between freelancers and clients is a
            crucial component of any platform facilitating freelance work
          </p>
        </div>
      </section>
      <section class="secondary-section">
        <div class="feature-box">
          <div class="feature-heading">
            <span class="material-symbols-outlined"> filter_alt </span>
            <h3>Filter Module</h3>
          </div>
          <p class="feature-text">
            Refine search results based on criteria, facilitating efficient
            navigation and tailored experiences.
          </p>
        </div>
      </section>
      <section class="secondary-section">
        <div class="feature-box">
          <div class="feature-heading">
            <span class="material-symbols-outlined"> star </span>
            <h3>Rating Module</h3>
          </div>
          <p class="feature-text">
            Evaluate freelancer's services, or clients, enhancing trust, and
            aiding decision-making processes.
          </p>
        </div>
      </section>
    </main>
    <hr />
    <main class="about-us">
      <img
        class="about-us-img"
        src="../../assets/logo/freelancing-img-2.svg"
        alt=""
      />
      <div>
        <h2>About Us</h2>
        <p class="about-us-text">
          Welcome to Localized Freelancing Platform, where we revolutionize the
          freelancing landscape, especially for students in Nepal. In today's
          dynamic world of employment, traditional nine-to-five jobs no longer
          define success. Instead, we provide a digital platform connecting
          students and professionals with clients seeking digitally deliverable
          services. At Localized Freelancing Platform, we understand the
          challenges faced by students in securing full-time jobs while juggling
          academic commitments.
        </p>
        <br />
        <p class="about-us-text">
          That's why we offer an innovative solution tailored to the unique
          needs of Nepali students. Our platform serves as a bridge between
          talented students and real-world opportunities, enabling them to apply
          their skills, gain practical experience, and earn income without the
          constraints of traditional employment.
        </p>
      </div>
    </main>
    <hr />
    <footer>
      <img
        class="logo-img-footer"
        src="../../assets/logo/test.png"
        alt="logo"
      />
      <p>&copy; Anish Dangol</p>
      <a href="#top"
        ><p>Back to Top</p>
        <span class="material-symbols-outlined"> arrow_drop_up </span></a
      >
    </footer>
  </body>
</html>
