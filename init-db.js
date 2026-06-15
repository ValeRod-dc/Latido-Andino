// Base de datos: latido_andino
db = db.getSiblingDB('latido_andino');

// Limpiar colecciones
db.usuarios.drop();
db.tramites.drop();
db.vehiculos.drop();
db.declaraciones_sag.drop();
db.pasos_fronterizos.drop();
db.integraciones.drop();

// ============================================
// USUARIOS con roles específicos
// ============================================
// Hash para "123456" (bcrypt)
const hash = "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K";

db.usuarios.insertMany([
    {
        name: "Viajero Demo",
        email: "viajero@example.com",
        password: hash,
        role: "viajero",
        rut: "12345678-9",
        nacionalidad: "Chilena",
        created_at: new Date()
    },
    {
        name: "Funcionario Aduana",
        email: "aduanas@aduana.cl",
        password: hash,
        role: "aduanas",
        cargo: "Fiscalizador",
        created_at: new Date()
    },
    {
        name: "Funcionario SAG",
        email: "sag@sag.cl",
        password: hash,
        role: "sag",
        cargo: "Inspector",
        created_at: new Date()
    },
    {
        name: "Funcionario PDI",
        email: "pdi@pdi.cl",
        password: hash,
        role: "pdi",
        cargo: "Policía Internacional",
        created_at: new Date()
    },
    {
        name: "Administrador Sistema",
        email: "admin@latidoandino.cl",
        password: hash,
        role: "admin",
        created_at: new Date()
    }
]);

// ============================================
// PASOS FRONTERIZOS
// ============================================
db.pasos_fronterizos.insertMany([
    {
        nombre: "Los Libertadores",
        region: "Valparaíso",
        pais_conexion: "Argentina",
        provincia: "Mendoza",
        activo: true
    },
    {
        nombre: "Paso Cardenal Samoré",
        region: "Los Lagos",
        pais_conexion: "Argentina",
        provincia: "Neuquén",
        activo: true
    },
    {
        nombre: "Paso Chungará",
        region: "Arica y Parinacota",
        pais_conexion: "Bolivia",
        provincia: "La Paz",
        activo: true
    }
]);

// ============================================
// TRÁMITES DE EJEMPLO
// ============================================
db.tramites.insertMany([
    {
        tipo: "ingreso",
        viajero_rut: "12345678-9",
        viajero_nombre: "Viajero Demo",
        paso_fronterizo: "Los Libertadores",
        fecha_tramite: new Date(),
        estado: "aprobado",
        documentos: {
            identidad: "verificado",
            declaracion_sag: "pendiente"
        },
        pase_agil_qr: "MOCK_QR_001",
        validacion_cruzada: {
            pdi: "aprobado",
            sag: "aprobado",
            interpol: "sin_alertas"
        },
        created_at: new Date()
    }
]);

// ============================================
// VEHÍCULOS DE EJEMPLO
// ============================================
db.vehiculos.insertMany([
    {
        patente: "AB1234",
        marca: "Toyota",
        modelo: "Corolla",
        año: 2020,
        color: "Gris",
        propietario_rut: "12345678-9",
        acuerdo_chile_argentina: {
            activo: true,
            fecha_emision: new Date(),
            fecha_vencimiento: new Date(Date.now() + 180*24*60*60*1000),
            formulario_id: "F-001"
        }
    }
]);

print("Base de datos Latido Andino inicializada");
print(`Usuarios: ${db.usuarios.countDocuments()}`);
print(`Pasos fronterizos: ${db.pasos_fronterizos.countDocuments()}`);
print(`Vehículos: ${db.vehiculos.countDocuments()}`);