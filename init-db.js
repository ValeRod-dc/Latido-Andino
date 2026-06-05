// Script para inicializar la base de datos con datos de ejemplo
// Este script se ejecutará automáticamente al iniciar MongoDB

db = db.getSiblingDB('coffee_shop');

// Limpiar colecciones existentes
db.users.drop();
db.products.drop();

// Crear usuarios de ejemplo
// Password hash para "123456": $2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K
db.users.insertMany([
    {
        name: "Cliente Demo",
        email: "cliente@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "cliente",
        created_at: new Date()
    },
    {
        name: "Trabajador Demo",
        email: "trabajador@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "trabajador",
        created_at: new Date()
    },
    {
        name: "Administrador Demo",
        email: "admin@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "administrador",
        created_at: new Date()
    }
]);

// Crear productos de café
db.products.insertMany([
    {
        name: "Espresso",
        description: "Café espresso italiano intenso y aromático, preparado con granos seleccionados",
        price: 3.50,
        size: "Regular",
        icon: "bi bi-cup-hot-fill",
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Cappuccino",
        description: "Espresso con leche vaporizada y espuma cremosa, perfecto para cualquier momento",
        price: 4.50,
        size: "Grande",
        icon: "bi bi-cup-straw",
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Latte",
        description: "Café latte suave con leche cremosa y un toque de vainilla",
        price: 4.75,
        size: "Grande",
        icon: "bi bi-cup-hot",
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Americano",
        description: "Espresso doble con agua caliente, ideal para los amantes del café fuerte",
        price: 3.75,
        size: "Regular",
        icon: "bi bi-cup",
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Mocha",
        description: "Deliciosa combinación de espresso, chocolate y leche vaporizada",
        price: 5.25,
        size: "Grande",
        icon: "bi bi-cup-hot-fill",
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Caramel Macchiato",
        description: "Espresso con leche, vainilla y caramelo, coronado con espuma",
        price: 5.50,
        size: "Grande",
        icon: "bi bi-cup-straw",
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Flat White",
        description: "Espresso doble con microespuma de leche, cremoso y suave",
        price: 4.25,
        size: "Regular",
        icon: "bi bi-cup-hot",
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Cold Brew",
        description: "Café preparado en frío durante 12 horas, servido con hielo",
        price: 4.50,
        size: "Grande",
        icon: "bi bi-cup-straw",
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Affogato",
        description: "Espresso caliente servido sobre helado de vainilla artesanal",
        price: 5.75,
        size: "Regular",
        icon: "bi bi-cup-hot-fill",
        active: true,
        is_new: true,
        created_at: new Date()
    }
]);

print("✅ Base de datos inicializada correctamente");
print("📊 Usuarios creados: " + db.users.count());
print("☕ Productos creados: " + db.products.count());
