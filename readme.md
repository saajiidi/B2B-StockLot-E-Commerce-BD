# B2B StockLot E-Commerce Platform - Becho

A comprehensive B2B e-commerce platform specifically designed for garments trading and stocklot business in Bangladesh. This platform enables wholesale buyers and sellers to connect, trade garments in bulk, and manage their inventory efficiently.

## Features

### Core E-Commerce Features
- **Product Catalog Management** - Comprehensive product listings with categories, specifications, and bulk pricing
- **B2B User Management** - Separate buyer and seller accounts with role-based permissions
- **Bulk Order Processing** - Handle large quantity orders with tiered pricing
- **Inventory Management** - Real-time stock tracking and low-stock alerts
- **Order Management** - Complete order lifecycle from quotation to delivery

### B2B Specific Features
- **Bulk Pricing Tiers** - Volume-based pricing for wholesale transactions
- **Quotation System** - Request and manage price quotes for large orders
- **Credit Management** - Business credit limits and payment terms
- **Supplier Network** - Connect with verified garment manufacturers and suppliers
- **Stocklot Listings** - Special section for surplus and stocklot inventory

### Business Features
- **Multi-vendor Support** - Multiple sellers can list their products
- **Advanced Search & Filters** - Find products by category, price, quantity, location
- **Business Profiles** - Detailed company profiles with verification badges
- **Communication Tools** - Built-in messaging system for buyer-seller communication

## Technology Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templates with Bootstrap
- **Database**: MySQL
- **Authentication**: Laravel Auth
- **File Storage**: Local/Cloud storage for product images

## Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/B2B-StockLot-E-Commerce-BD.git
   cd B2B-StockLot-E-Commerce-BD
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a MySQL database
   - Update `.env` file with your database credentials
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run dev
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## Project Structure

```
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeds/              # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   └── assets/             # Frontend assets
└── routes/                 # Application routes
```

## API Documentation

The platform provides RESTful APIs for:
- Product management
- Order processing
- User authentication
- Inventory tracking

API documentation is available at `/api/documentation` when running in development mode.

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## Development Roadmap

- [ ] Upgrade to Laravel 10.x LTS
- [ ] Implement advanced search with Elasticsearch
- [ ] Add real-time notifications
- [ ] Mobile app development
- [ ] Payment gateway integration
- [ ] Multi-language support (Bengali/English)
- [ ] Advanced analytics dashboard

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Email: support@becho.com
- Documentation: [docs.becho.com](https://docs.becho.com)
- Issues: [GitHub Issues](https://github.com/your-username/B2B-StockLot-E-Commerce-BD/issues)

## About Becho

Becho is designed to revolutionize the garments trading industry in Bangladesh by providing a modern, efficient platform for B2B transactions. Our goal is to connect manufacturers, wholesalers, and retailers in a seamless digital marketplace.
