# StreamPlus Subscription  
This is a Symfony-based multi-step user onboarding form for StreamPlus Subscription. The form collects user information, address details, and payment information (for premium users). Upon successful submission, users are redirected to a confirmation page before completing the process.  

## Requirements  
- PHP >= 8.1  
- Composer  
- MySQL  
- Symfony CLI (optional, but recommended)  

## Installation  
1. **Clone the repository**:  
   `git clone https://github.com/your-repo/streamplus-subscription.git && cd streamplus-subscription`  
2. **Install dependencies**:  
   `composer install`  
3. **Set up the environment file**:  
   - Create a `.env` file in the root directory.  
   - Update the database credentials:  
     `DATABASE_URL="mysql://root:password@127.0.0.1:3306/streamplus_subscription?serverVersion=8.0"`  
4. **Create and configure the database**:  
   `symfony console doctrine:database:create && symfony console doctrine:migrations:migrate`  
5. **Run the Symfony development server**:  
   `symfony serve`  
6. **Access the application**:  
   Open your browser and go to [http://127.0.0.1:8000](http://127.0.0.1:8000) to start the subscription flow.  

## Author  
Ali Habieb  
