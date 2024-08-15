<x-app-layout>

    <div class="relative min-h-screen md:flex">

        {{-- Sidebar --}}
        <div id="sidebar" class="z-50 bg-white w-64 absolute inset-y-0 left-0 transform -translate-x-full transition duration-200 ease-in-out md:relative md:translate-x-0">

            {{-- Header --}}
            <div class="w-100 flex-none bg-white border-b-2 border-b-grey-200 flex flex-row p-5 pr-0 justify-between items-center h-20 ">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="mx-auto">

                    <img class="h-16" src="{{ asset('storage/img/logo2.png') }}" alt="Logo">

                </a>

                {{-- Side Menu hide button --}}
                <button  type="button" title="Cerrar Menú" id="sidebar-menu-button" class="md:hidden mr-2 inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                </button>

            </div>

            {{-- Nav --}}
            <nav class="p-4 text-rojo">

                <a href="#usuarios" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>

                    Usuarios
                </a>

                <a href="#servicios" class="mb-3 capitalize font-medium text-md hover:text-red-600 transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                    </svg>

                    Servcios
                </a>

                <a href="#categorias" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z" />
                    </svg>

                    Categorías
                </a>

                <a href="#umas" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    UMAS
                </a>

                <a href="#tramites" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m-6 3.75l3 3m0 0l3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                    </svg>

                    Trámites
                </a>

                <a href="#dependencias" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>

                    Dependencias
                </a>

                <a href="#notarias" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>

                    Notarias
                </a>

                <a href="#entrada" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>

                    Entrada
                </a>

                <a href="#entrega" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4 " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>

                    Entrega
                </a>

                <a href="#consultas" class="mb-3 capitalize font-medium text-md transition ease-in-out duration-500 flex hover  hover:bg-gray-100 p-2 px-4 rounded-xl">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    Consultas
                </a>

            </nav>

        </div>

        {{-- Content --}}
        <div class="flex-1 flex-col flex max-h-screen overflow-x-auto min-h-screen">

            {{-- Mobile --}}
            <div class="w-100 bg-white border-b-2 border-b-grey-200 flex-none flex flex-row p-5 justify-between items-center h-20">

                <!-- Mobile menu button-->
                <div class="flex items-center">

                    <button  type="button" title="Abrir Menú" id="mobile-menu-button" class="md:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">

                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                    </button>

                </div>

                {{-- Logo --}}
                <p class="font-semibold text-2xl text-rojo">Manual de Usuario</p>

                <div></div>

            </div>

            {{-- Main Content --}}
            <div class="bg-white flex-1 overflow-y-auto py-8 md:border-l-2 border-l-grey-200">

                <div class="lg:w-2/3 mx-auto rounded-xl">

                    <div class="capitulo mb-10" id="introduccion">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Introducción</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                El Sistema Trámitesl, tiene como propósito administrar servicios emitidos por el Instituto Registral y Catastral de Michoacán.
                                El sistema permite generar y dar seguimiento a los trmites que son solicitados.
                            </p>

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="usuarios">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Usuarios</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de usuarios lleva el control del registro de los usuarios del sistema.
                            </p>

                            <p>
                                <strong>Busqueda de usuario:</strong>
                                puede hacer busqueda de usuarios por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nuevo usuario:</strong>
                                puede agregar un nuevo usuario haciendo click el el botón "Agregar nuevo usuario" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_crear.jpg') }}" alt="Imágen crear">

                            <p>
                                <strong>Editar usuario:</strong>
                                cada usuario tiene asociado dos botones de acciones, puede editar un usuario haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información del usuario para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_editar.jpg') }}" alt="Imágen buscar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_modal_editar.jpg') }}" alt="Imágen editar">

                            <p>
                                <strong>Borrar usuario:</strong>
                                puede borrar un usuario haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/usuarios_borrar.jpg') }}" alt="Imágen borrar">

                            <p>
                                Al crear un usuario, su credenciales para iniciar sesión seran su correo y la contraseña "sistema", al tratar de iniciar sesión le pedira actualizar su contraseña.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/actualizar_contraseña.jpg') }}" alt="Imágen contraseña">

                            <p>
                                Puede revisar su perfil de usuario haciendo click en el circulo superior izquierdo en la opción "Mi perfil"
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/perfil.jpg') }}" alt="Imágen perfil">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="servicios">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Servicios</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de servicios lleva los registros de todos los servicios que se ofrecen.
                            </p>

                            <p>
                                <strong>Busqueda de servicio:</strong>
                                puede hacer busqueda de servicios por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/servicios_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nuevo servicio:</strong>
                                puede agregar una nuevo servicio haciendo click el el botón "Agregar nueva servicio" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/servicios_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar servicio:</strong>
                                cada servicio tiene asociado dos botones de acciones, puede editar un servicio haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información del servicio para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/servicios_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/servicios_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar servicio:</strong>
                                puede borrar un servicio haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/servicios_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="categorias">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Categorías</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de categorías lleva los registros de todos los categorías a las que pertenecen los servicios.
                            </p>

                            <p>
                                <strong>Busqueda de categoría:</strong>
                                puede hacer busqueda de categorías por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/categorias_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nueva categoría:</strong>
                                puede agregar una nueva categoría haciendo click el el botón "Agregar nueva categoría" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/categorias_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar categoría:</strong>
                                cada categoría tiene asociada dos botones de acciones, puede editar un categoría haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la categoría para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/categorias_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/categorias_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar categoría:</strong>
                                puede borrar una categoría haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/categorias_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="umas">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">UMAS</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de UMAS lleva el registro de las unidades de medida y actualización necesarias para el cálculo del precio de los servicios.
                            </p>

                            <p>
                                <strong>Busqueda de uma:</strong>
                                puede hacer busqueda de umas por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/umas_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Agregar nueva uma:</strong>
                                puede agregar una nueva uma haciendo click el el botón "Agregar nueva uma" esta acción deplegará una ventana modal
                                en la cual se ingresará la información necesaria para el registro. Al hacer click en el botón "Guardar" se generará el registro con los datos
                                proporcionados. Al hacer click en cerrar se cerrará la ventana modal borrando la información proporcionada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/umas_modal_crear.jpg') }}" alt="Imágen modal crear">

                            <p>
                                <strong>Editar uma:</strong>
                                cada uma tiene asociada dos botones de acciones, puede editar una uma haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la uma para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/umas_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/umas_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar uma:</strong>
                                puede borrar una uma haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/umas_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="tramites">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Trámites</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de trámites lleva el control del registro de los trámites generados.
                            </p>

                            <p>
                                <strong>Busqueda de trámites:</strong>
                                puede hacer busqueda de trámites por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/tramites_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Editar trámite:</strong>
                                cada trámite tiene asociado dos botones de acciones, puede editar un trámite haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la trámite para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/tramites_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/tramites_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar trámite:</strong>
                                puede borrar un trámite haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/tramites_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="dependencias">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Dependencias</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de dependencias lleva el control del registro de los dependencias necesarias para el registro de trámites.
                            </p>

                            <p>
                                <strong>Busqueda de dependencias:</strong>
                                puede hacer busqueda de dependencias por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Editar dependencia:</strong>
                                cada dependencia tiene asociada dos botones de acciones, puede editar una dependencia haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la dependencia para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar dependencia:</strong>
                                puede borrar un dependencia haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/dependencias_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="notarias">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Notarias</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de notarias lleva el control del registro de los notarias necesarias para el registro de trámites.
                            </p>

                            <p>
                                <strong>Busqueda de notarias:</strong>
                                puede hacer busqueda de notarias por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/notarias_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Editar notaría:</strong>
                                cada notaría tiene asociada dos botones de acciones, puede editar una notaría haciendo click el el botón "Editar" esta acción deplegará una ventana modal
                                en la cual se mostrará la información de la notaría para actualizar.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/notarias_editar.jpg') }}" alt="Imágen editar">

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/notarias_modal_editar.jpg') }}" alt="Imágen editar modal">

                            <p>
                                <strong>Borrar notaría:</strong>
                                puede borrar un notaría haciendo click el el botón "Borrar" esta acción deplegará una ventana modal
                                en la cual se mostrará una advertencia, dando la opcion de cancelar o borrar la información.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/notarias_borrar.jpg') }}" alt="Imágen borrar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="entrada">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Entrada</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de entrada lleva a cabo el registro de lo trámites solicitados.
                            </p>

                            <p>
                                <strong>Selección de categoría:</strong>
                                Es necesario como primer paso seleccionar la categoría del trámite a construir
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_seleccion_categoria.jpg') }}" alt="Seleccionar categoría">

                            <p>
                                <strong>Selección de servicio:</strong>
                                Es necesario como segundo paso seleccionar el trámite a construir. Cada servicio desplegara los campos necesarios para su construcción.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_seleccion_servicio.jpg') }}" alt="Seleccionar servicio">

                            <p>
                                <strong>Crear trámite:</strong>
                                Una vez llenado los campos requeridos puede hacer click en el boton "Crear nuevo trámite".
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_crear.jpg') }}" alt="Crear trámite">

                            <p>
                                <strong>Orden de pago:</strong>
                                Al crear el trámite automaticamente se abrira una nueva pestaña en su navegador con la orden de pago, en caso de no ver la pestaña revisar que el navegador no tenga bloqueadas las ventanas emergentes.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_recibo.jpg') }}" alt="Orden de pago">

                            <p>
                                <strong>Buscar trámite:</strong>
                                Puede buscar tramites por su número de control, esto deplegara la información del trámite unicamente si el estado del trámite es "Nuevo", mostrando tres posibles acciones
                                editarlo, reimprimir la orden de pago y validar si es que la orden de pago ya ha sido pagada.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrada_resultado_busqueda.jpg') }}" alt="Busqueda">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="entrega">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Entrega</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                La sección de entrega se encarga de recibir los documentos resultantes del trámite para ser entregados al solicitante.
                            </p>

                            <p>
                                <strong>Busqueda de trámite:</strong>
                                puede hacer busqueda de trámites por cualquiera de las columnas que muestra la tabla.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrega_buscar.jpg') }}" alt="Imágen buscar">

                            <p>
                                <strong>Recibir trámite:</strong>
                                Puede recibir el támite haciendo click en el boton "Recibir".
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrega_recibir.jpg') }}" alt="Imágen editar">

                            <p>
                                <strong>Entrega trámite:</strong>
                                Una vez recibido el trámite puede hacer click en "Entregar" para entregar la documentación final al solicitante finalizado así l trámite.
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/entrega_entregar.jpg') }}" alt="Imágen editar">

                        </div>

                    </div>

                    <div class="capitulo mb-10" id="consultas">

                        <h2 class="text-2xl tracking-widest py-3 px-6 text-gray-600 rounded-xl border-b-2 border-gray-500 font-semibold mb-6  bg-white">Consultas</h2>

                        <div class="  px-3">

                            <p class="mb-2">
                                En la sección de consultas puede ver cualquier tramite necesario buscandolo por su número de control
                            </p>

                            <img class="mb-4 mt-4 rounded mx-auto" src="{{ asset('storage/img/manual/consultas.jpg') }}" alt="Imágen editar">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>

        const btn_close = document.getElementById('sidebar-menu-button');
        const btn_open = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');

        btn_open.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        btn_close.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        /* Change nav profile image */
        window.addEventListener('nav-profile-img', event => {

            document.getElementById('nav-profile').setAttribute('src', event.detail.img);

        });

    </script>

</x-app-layout>
