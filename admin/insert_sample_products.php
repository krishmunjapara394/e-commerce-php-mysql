    <?php
include('../includes/connect.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    die("Access denied. Please login as admin.");
}

// Sample products data - 60 products across different categories with reliable online image links
// Using Unsplash Source API which provides reliable, working image URLs
$products = [
    // Mobiles (Category 1)
    ['iPhone 15 Pro Max', 'Latest iPhone with A17 Pro chip, 48MP camera, and titanium design. Features 6.7-inch Super Retina XDR display and up to 1TB storage.', 'iphone, apple, smartphone, mobile, ios', 1, 7, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 1199.99],
    ['Samsung Galaxy S24 Ultra', 'Premium Android phone with S Pen, 200MP camera, Snapdragon 8 Gen 3, and 6.8-inch Dynamic AMOLED display.', 'samsung, galaxy, android, smartphone, mobile', 1, 10, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 1299.99],
    ['Oppo Find X6 Pro', 'Flagship phone with triple 50MP cameras, Snapdragon 8 Gen 2, and 6.82-inch LTPO AMOLED display.', 'oppo, find x6, android, smartphone', 1, 8, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 899.99],
    ['Nokia G60 5G', 'Durable 5G smartphone with pure Android, 6.58-inch display, and 3-day battery life.', 'nokia, 5g, android, durable, mobile', 1, 13, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 349.99],
    ['Samsung Galaxy A54', 'Mid-range phone with 50MP camera, 6.4-inch Super AMOLED, and 5G connectivity.', 'samsung, galaxy a54, mid-range, android', 1, 10, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 449.99],
    ['iPhone 14', 'Previous generation iPhone with A15 Bionic chip, dual camera system, and 6.1-inch display.', 'iphone, apple, smartphone, ios', 1, 7, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=500&fit=crop', 699.99],
    
    // Books (Category 2) - Each book has unique images
    ['The Great Gatsby', 'Classic American novel by F. Scott Fitzgerald about the Jazz Age and the American Dream.', 'book, novel, classic, literature, fiction', 2, 9, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=500&h=500&fit=crop', 12.99],
    ['To Kill a Mockingbird', 'Harper Lee\'s Pulitzer Prize-winning novel about racial injustice in the American South.', 'book, novel, classic, literature, harper lee', 2, 9, 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?w=500&h=500&fit=crop', 14.99],
    ['1984 by George Orwell', 'Dystopian novel about totalitarianism, surveillance, and thought control.', 'book, novel, dystopian, orwell, classic', 2, 9, 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1532619675605-1d6d3c85b0c5?w=500&h=500&fit=crop', 13.99],
    ['Pride and Prejudice', 'Jane Austen\'s romantic novel about Elizabeth Bennet and Mr. Darcy.', 'book, novel, romance, jane austen, classic', 2, 9, 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=500&h=500&fit=crop', 11.99],
    ['The Catcher in the Rye', 'J.D. Salinger\'s controversial novel about teenage rebellion and alienation.', 'book, novel, classic, salinger, fiction', 2, 9, 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1532619675605-1d6d3c85b0c5?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=500&h=500&fit=crop', 12.99],
    ['Harry Potter Complete Set', 'All 7 books in the Harry Potter series by J.K. Rowling in a beautiful box set.', 'book, harry potter, fantasy, rowling, box set', 2, 9, 'https://picsum.photos/seed/harrypotter1/500/500', 'https://picsum.photos/seed/harrypotter2/500/500', 'https://picsum.photos/seed/harrypotter3/500/500', 89.99],
    
    // Food (Category 3)
    ['Premium Coffee Beans', '100% Arabica coffee beans, medium roast, 1kg pack. Rich and smooth flavor.', 'coffee, beans, arabica, premium, food', 3, 9, 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=500&h=500&fit=crop', 24.99],
    ['Organic Green Tea', 'Premium organic green tea leaves, 250g. Rich in antioxidants and natural flavor.', 'tea, green tea, organic, healthy, food', 3, 9, 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=500&h=500&fit=crop', 18.99],
    ['Dark Chocolate Bar', 'Premium 85% dark chocolate, 200g. Made with finest cocoa beans.', 'chocolate, dark chocolate, sweet, food', 3, 9, 'https://picsum.photos/seed/chocolate1/500/500', 'https://picsum.photos/seed/chocolate2/500/500', 'https://picsum.photos/seed/chocolate3/500/500', 8.99],
    ['Honey Jar 500g', 'Pure natural honey, 500g jar. Collected from wildflower fields.', 'honey, natural, organic, sweet, food', 3, 9, 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=500&h=500&fit=crop', 15.99],
    ['Olive Oil Extra Virgin', 'Premium extra virgin olive oil, 750ml. Cold-pressed from Mediterranean olives.', 'olive oil, cooking, premium, food', 3, 9, 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500&h=500&fit=crop', 22.99],
    ['Gourmet Pasta Set', 'Premium Italian pasta set with 5 varieties, 500g each. Made from durum wheat.', 'pasta, italian, gourmet, food, cooking', 3, 9, 'https://images.unsplash.com/photo-1551462147-85890c2a0d00?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1551462147-85890c2a0d00?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1551462147-85890c2a0d00?w=500&h=500&fit=crop', 19.99],
    
    // Clothes (Category 4)
    ['Nike Air Max 270', 'Comfortable running shoes with Air Max cushioning. Available in multiple colors.', 'nike, shoes, sneakers, running, sports', 4, 3, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop', 129.99],
    ['Polo Ralph Lauren Shirt', 'Classic cotton polo shirt. Perfect for casual and semi-formal occasions.', 'polo, shirt, casual, cotton, clothing', 4, 5, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 79.99],
    ['Nike Dri-FIT T-Shirt', 'Moisture-wicking athletic t-shirt. Perfect for workouts and sports.', 'nike, t-shirt, athletic, sports, clothing', 4, 3, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop', 34.99],
    ['Denim Jeans Classic', 'Classic fit denim jeans. Comfortable and durable for everyday wear.', 'jeans, denim, casual, pants, clothing', 4, 9, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=500&h=500&fit=crop', 59.99],
    ['Winter Jacket', 'Warm and stylish winter jacket. Waterproof and windproof design.', 'jacket, winter, warm, outerwear, clothing', 4, 9, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500&h=500&fit=crop', 149.99],
    ['Leather Belt', 'Genuine leather belt with classic buckle. Adjustable size.', 'belt, leather, accessory, fashion', 4, 9, 'https://picsum.photos/seed/belt1/500/500', 'https://picsum.photos/seed/belt2/500/500', 'https://picsum.photos/seed/belt3/500/500', 39.99],
    
    // HeadPhones (Category 5)
    ['Sony WH-1000XM5', 'Premium noise-canceling headphones with 30-hour battery life and LDAC support.', 'sony, headphones, wireless, noise canceling, audio', 5, 9, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 399.99],
    ['Apple AirPods Pro', 'Wireless earbuds with active noise cancellation and spatial audio.', 'apple, airpods, wireless, earbuds, audio', 5, 7, 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=500&h=500&fit=crop', 249.99],
    ['Samsung Galaxy Buds2 Pro', 'Premium wireless earbuds with intelligent ANC and 360 Audio.', 'samsung, earbuds, wireless, audio, galaxy', 5, 10, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 199.99],
    ['Beats Studio3 Wireless', 'Over-ear headphones with Pure ANC and Apple W1 chip integration.', 'beats, headphones, wireless, apple, audio', 5, 9, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 349.99],
    ['JBL Tune 760NC', 'Wireless over-ear headphones with active noise cancellation and 35-hour battery.', 'jbl, headphones, wireless, noise canceling', 5, 9, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 129.99],
    ['Sennheiser HD 450BT', 'Wireless headphones with active noise cancellation and 30-hour battery life.', 'sennheiser, headphones, wireless, audio', 5, 9, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', 179.99],
    
    // Electronics (Category 6)
    ['Dell XPS 15 Laptop', 'Premium 15-inch laptop with Intel i7, 16GB RAM, 512GB SSD, and 4K display.', 'dell, laptop, xps, computer, electronics', 6, 4, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 1499.99],
    ['HP Pavilion 14', '14-inch laptop with AMD Ryzen 5, 8GB RAM, 256GB SSD. Perfect for work and study.', 'hp, laptop, pavilion, computer, electronics', 6, 6, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 599.99],
    ['Lenovo ThinkPad X1', 'Business laptop with Intel i7, 16GB RAM, 1TB SSD, and 14-inch display.', 'lenovo, laptop, thinkpad, business, electronics', 6, 2, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 1299.99],
    ['Apple MacBook Pro 16', '16-inch MacBook Pro with M3 Pro chip, 18GB RAM, and 512GB SSD.', 'apple, macbook, laptop, computer, electronics', 6, 7, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&h=500&fit=crop', 2499.99],
    ['Samsung 4K Smart TV 55', '55-inch 4K UHD Smart TV with HDR, Tizen OS, and voice control.', 'samsung, tv, smart tv, 4k, electronics', 6, 10, 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=500&h=500&fit=crop', 799.99],
    ['Canon EOS R6 Mark II', 'Full-frame mirrorless camera with 24MP sensor, 4K video, and advanced autofocus.', 'canon, camera, dslr, photography, electronics', 6, 1, 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 2499.99],
    ['Nikon D850 DSLR', 'Professional DSLR camera with 45.7MP sensor, 4K video, and advanced features.', 'nikon, camera, dslr, photography, electronics', 6, 9, 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 2799.99],
    ['iPad Pro 12.9', '12.9-inch iPad Pro with M2 chip, 256GB storage, and Liquid Retina XDR display.', 'apple, ipad, tablet, electronics', 6, 7, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 1099.99],
    ['Samsung Galaxy Tab S9', '12.4-inch Android tablet with Snapdragon 8 Gen 2, 256GB storage, and S Pen.', 'samsung, tablet, android, electronics', 6, 10, 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&h=500&fit=crop', 899.99],
    ['PlayStation 5 Console', 'Next-gen gaming console with 4K gaming, ray tracing, and ultra-fast SSD.', 'playstation, ps5, gaming, console, electronics', 6, 9, 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 499.99],
    ['Xbox Series X', 'Next-gen gaming console with 4K gaming, 120fps support, and quick resume.', 'xbox, gaming, console, microsoft, electronics', 6, 9, 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 499.99],
    ['Nintendo Switch OLED', 'Gaming console with 7-inch OLED screen, detachable controllers, and portable design.', 'nintendo, switch, gaming, console, electronics', 6, 9, 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=500&h=500&fit=crop', 349.99],
    ['LG 27-inch 4K Monitor', '27-inch 4K UHD monitor with HDR10, USB-C, and adjustable stand.', 'lg, monitor, 4k, display, electronics', 6, 9, 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&h=500&fit=crop', 399.99],
    ['Logitech MX Master 3', 'Premium wireless mouse with ergonomic design, precision tracking, and multi-device support.', 'logitech, mouse, wireless, computer, electronics', 6, 9, 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 99.99],
    ['Mechanical Keyboard RGB', 'Gaming mechanical keyboard with RGB backlight, Cherry MX switches, and programmable keys.', 'keyboard, mechanical, gaming, rgb, electronics', 6, 9, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 149.99],
    ['External SSD 1TB', 'Portable 1TB external SSD with USB-C, fast transfer speeds up to 1050MB/s.', 'ssd, external, storage, usb-c, electronics', 6, 9, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&h=500&fit=crop', 89.99],
    ['Wireless Charging Pad', 'Fast wireless charging pad compatible with Qi-enabled devices. 15W fast charging.', 'wireless charger, charging pad, qi, electronics', 6, 9, 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=500&h=500&fit=crop', 29.99],
    ['Smart Watch Series 8', 'Advanced smartwatch with health tracking, GPS, and always-on display.', 'smartwatch, watch, fitness, wearable, electronics', 6, 7, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 399.99],
    ['Fitness Tracker Band', 'Activity tracker with heart rate monitor, sleep tracking, and 7-day battery.', 'fitness tracker, band, health, wearable, electronics', 6, 9, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop', 79.99],
    
    // Accessories (Category 7)
    ['Phone Case iPhone 15', 'Protective phone case with shock absorption and raised edges for screen protection.', 'phone case, iphone, protection, accessory', 7, 7, 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 24.99],
    ['Laptop Stand Aluminum', 'Adjustable aluminum laptop stand for better ergonomics and cooling.', 'laptop stand, aluminum, ergonomic, accessory', 7, 9, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=500&h=500&fit=crop', 39.99],
    ['USB-C Hub 7-in-1', 'Multi-port USB-C hub with HDMI, USB 3.0, SD card reader, and power delivery.', 'usb hub, usb-c, adapter, accessory, electronics', 7, 9, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 49.99],
    ['Camera Tripod Professional', 'Heavy-duty tripod with 360-degree rotation, extendable legs, and phone mount.', 'tripod, camera, photography, accessory', 7, 9, 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=500&fit=crop', 89.99],
    ['Car Phone Mount', 'Magnetic car phone mount with 360-degree rotation and strong grip.', 'car mount, phone mount, magnetic, accessory', 7, 9, 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 19.99],
    ['Bluetooth Speaker Portable', 'Waterproof portable Bluetooth speaker with 20-hour battery and 360-degree sound.', 'bluetooth speaker, portable, waterproof, audio, accessory', 7, 9, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500&h=500&fit=crop', 79.99],
    ['Power Bank 20000mAh', 'High-capacity power bank with fast charging, dual USB ports, and LED indicator.', 'power bank, charger, portable, accessory', 7, 9, 'https://picsum.photos/seed/powerbank1/500/500', 'https://picsum.photos/seed/powerbank2/500/500', 'https://picsum.photos/seed/powerbank3/500/500', 34.99],
    ['Screen Protector Glass', 'Tempered glass screen protector with 9H hardness and bubble-free installation.', 'screen protector, glass, protection, accessory', 7, 9, 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1601972602237-8c79241e468b?w=500&h=500&fit=crop', 12.99],
    ['Laptop Backpack', 'Durable laptop backpack with padded compartment, multiple pockets, and USB charging port.', 'backpack, laptop bag, travel, accessory', 7, 9, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop', 59.99],
    ['Webcam HD 1080p', 'Full HD 1080p webcam with autofocus, built-in microphone, and privacy cover.', 'webcam, camera, video, streaming, accessory', 7, 9, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 69.99],
    ['Gaming Mouse Pad', 'Large RGB gaming mouse pad with smooth surface and customizable lighting effects.', 'mouse pad, gaming, rgb, accessory', 7, 9, 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=500&h=500&fit=crop', 24.99],
    ['Cable Management Kit', 'Cable organizer kit with clips, ties, and sleeves for neat desk setup.', 'cable management, organizer, accessory', 7, 9, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500&h=500&fit=crop', 14.99],
];

// Check if already inserted (prevent duplicate runs)
$check_query = "SELECT COUNT(*) as count FROM `products`";
$check_result = mysqli_query($con, $check_query);
$existing_count = mysqli_fetch_assoc($check_result)['count'];

// Insert products
$inserted = 0;
$updated = 0;
$errors = [];

// Only insert if button is clicked
if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    foreach ($products as $product) {
        $title = mysqli_real_escape_string($con, $product[0]);
        $description = mysqli_real_escape_string($con, $product[1]);
        $keywords = mysqli_real_escape_string($con, $product[2]);
        $category_id = $product[3];
        $brand_id = $product[4];
        $image_one = mysqli_real_escape_string($con, $product[5]);
        $image_two = mysqli_real_escape_string($con, $product[6]);
        $image_three = mysqli_real_escape_string($con, $product[7]);
        $price = $product[8];
        
        // Check if product already exists
        $check_product = "SELECT * FROM `products` WHERE product_title = '$title'";
        $check_product_result = mysqli_query($con, $check_product);
        
        if (mysqli_num_rows($check_product_result) > 0) {
            // Update existing product instead of skipping
            $update_query = "UPDATE `products` SET 
                            product_description = '$description',
                            product_keywords = '$keywords',
                            category_id = $category_id,
                            brand_id = $brand_id,
                            product_image_one = '$image_one',
                            product_image_two = '$image_two',
                            product_image_three = '$image_three',
                            product_price = $price,
                            date = NOW(),
                            status = 'true'
                            WHERE product_title = '$title'";
            
            $update_result = mysqli_query($con, $update_query);
            
            if ($update_result) {
                $updated++;
            } else {
                $errors[] = "Failed to update: $title - " . mysqli_error($con);
            }
        } else {
            // Insert new product
            $insert_query = "INSERT INTO `products` (product_title, product_description, product_keywords, category_id, brand_id, product_image_one, product_image_two, product_image_three, product_price, date, status) 
                             VALUES ('$title', '$description', '$keywords', $category_id, $brand_id, '$image_one', '$image_two', '$image_three', $price, NOW(), 'true')";
            
            $result = mysqli_query($con, $insert_query);
            
            if ($result) {
                $inserted++;
            } else {
                $errors[] = "Failed to insert: $title - " . mysqli_error($con);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Sample Products</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .result-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 50px auto;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #1e40af;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h2 class="mb-4"><i class="fas fa-database"></i> Insert Sample Products</h2>
        
        <div class="info-box">
            <h5><i class="fas fa-info-circle"></i> About This Tool</h5>
            <p class="mb-0">This will insert/update <strong>60 sample products</strong> across all categories (Mobiles, Books, Food, Clothes, HeadPhones, Electronics, Accessories) with realistic product data and reliable Unsplash image URLs.</p>
            <p class="mb-0 mt-2"><strong>Current products in database:</strong> <?php echo $existing_count; ?></p>
            <p class="mb-0 mt-2"><strong>Note:</strong> Existing products will be updated with new images and data.</p>
        </div>
        
        <?php if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes'): ?>
            <?php if ($inserted > 0 || $updated > 0): ?>
            <div class="alert alert-success">
                <h5><i class="fas fa-check-circle"></i> Success!</h5>
                <?php if ($inserted > 0): ?>
                <p><strong><?php echo $inserted; ?></strong> new products inserted successfully.</p>
                <?php endif; ?>
                <?php if ($updated > 0): ?>
                <p><strong><?php echo $updated; ?></strong> existing products updated with new images and data.</p>
                <?php endif; ?>
                <p class="mb-0">Total processed: <strong><?php echo ($inserted + $updated); ?></strong> out of <?php echo count($products); ?> products.</p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-3">
                <h5><i class="fas fa-exclamation-triangle"></i> Errors:</h5>
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="index.php?view_products" class="btn btn-primary"><i class="fas fa-eye"></i> View All Products</a>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Warning</h5>
                <p>This will add/update 60 products to your database. Make sure you want to proceed.</p>
                <p><strong>Note:</strong> Products with matching titles will be updated (not skipped) with new images and data.</p>
            </div>
            
            <div class="mt-4">
                <a href="?confirm=yes" class="btn btn-success btn-lg"><i class="fas fa-check"></i> Yes, Insert/Update 60 Products</a>
                <a href="index.php" class="btn btn-secondary btn-lg"><i class="fas fa-times"></i> Cancel</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
