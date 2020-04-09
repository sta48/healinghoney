<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width-device-width">
    <meta name="description" content="Affordable and professional web design">
    <meta name="keywords" content="web design, affordable web design">
    <meta name="author" content="Sayeeda Aishee">
    <title>Acme Web Design | About</title>
    <link rel="stylesheet" href="./css/style.css">
  </head>

  <body>
      <header>
        <div class="container">
          <div id="branding">
            <h1><span class="highlight">Acme</span> Web Design</h1>
          </div>
          <nav>
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="AllAboutCups.html">All About Cups</a></li>
              <li class="current"><a href="TheProject.html">The Project</a></li>
              <li><a href="Inspiration.html">Inspiration</a></li>
            </ul>
          </nav>
        </div>
      </header>

      <section id="newsletter">
        <div class="container">
          <h1>Sucscribe to our Newsletter!</h1>
          <form>
            <input type="email" placeholder="Enter Email">
            <button type = "submit" class="button_1">Subscribe</button>
          </form>
        </div>
      </section>

      <section id="main">
        <div class="container">
          <article id="main-col">
            <h1 class= page-title> Services</h1>
            <ul id="services">
              <li>
                <h3> Website Design </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut hendrerit elit. Proin vehicula posuere libero, sed mollis felis accumsan id. Curabitur tristique condimentum congue.</p>
                <p>Pricing:$100-$1000</p>
              </li>
              <li>
                <h3> Website Maintanace </h3>
                <p>Duis volutpat elementum odio, vitae aliquam sapien tincidunt eu. Duis eget lectus vehicula, molestie massa non, ultrices odio. Morbi sit amet magna non libero sagittis sodales.</p>
                <p>Pricing:$100-$200 per month</p>
              </li>
              <li>
                <h3> Website Hosting </h3>
                <p>Nullam pharetra dictum tellus, vitae maximus nisl mollis at. Duis eu eleifend justo. Etiam consequat turpis ipsum, eu pellentesque purus luctus vel.</p>
                <p>Pricing:$25 per month</p>
              </li>
            </ul>
          </article>
            <aside id="sidebar">
              <div class= "dark">
                <h3>Get a Quote</h3>
                <form class="quote">
                  <div>
                    <label>Name</label><br>
                    <input type= "text" placeholder="Name">
                  </div>
                  <div>
                    <label> Email</label><br>
                    <input type="email" placeholder="Email">
                  </div>
                  <div>
                    <label> Message</label><br>
                    <textarea placeholder="Type text here..."></textarea>
                  </div>
                    <button class="button_1" type="Submit">Send</button>
                </form>
              </div>
            </aside>
      </div>
      </section>

      <footer>
        <p>Acme Web Design, Copyright &copy; 2020</p>
      </footer>
  </body>
</html>
