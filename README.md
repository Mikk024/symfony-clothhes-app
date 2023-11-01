# Symfony Zalando-like E-commerce App
![home](https://imageupload.io/ib/afSskGF7DwHAErI_1698792747.png)
****
![men-clothhes](https://imageupload.io/ib/QxQGSsTvoLeWaBl_1698792920.png)
****
![cart](https://imageupload.io/ib/AcR0w02LHJn4iB7_1698793158.png)
****
![login](https://imageupload.io/ib/shWm1hfArarcNjP_1698793317.png)

## Demo App
https://www.main-bvxea6i-4vryfxvktukoo.eu-5.platformsh.site/men


## Description

This project is a full-fledged e-commerce platform similar to Zalando, built using Symfony (PHP) and MySQL. It incorporates essential features such as a basic Stripe payment gateway, Symfony Messenger for asynchronous tasks, extensive validation, and comprehensive testing. Twig templates are utilized for efficient rendering of dynamic content.

## Key Features

- Browse and search for a wide range of products
- Add items to cart and proceed to checkout
- Secure payments using Stripe (Test Mode, card: 4242 4242 4242 4242)
- Symfony Messenger for asynchronous tasks
- Implements robust validation for user inputs
- Admin role for managing products, categories, and orders
- Email notifications for order confirmation
- 
## Technologies Used

- **Backend:** Symfony (PHP), MySQL
- **Frontend:** Twig (templating), Tailwind CSS (styling)
- **Payment Gateway:** Stripe 
- **Asynchronous Processing:** Symfony Messenger
- **Testing:** PHPUnit

## Setup Instructions

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Mikk024/symfony-clothhes-app.git
   
2. **Install Dependencies:**
   ```bash
   composer install
   npm install
3. **Setup database:**
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

4. **Serve project**
    ```bash
    symfony server:start
