# EAP - Especificação da Arquitetura e Protótipo

3 de janeiro de 2022

## Tema Geral

Social Networks

## **Autores**

Cristina Pêra (up201907321)

Luís Soares (up201406356)

Mateus Silva (up201906232)

Melissa Silva (up201905076)

## A7: Especificação de Recursos *Web*

### Visão Global

Nesta secção, apresentamos uma visão global da aplicação *web* a implementar, onde os módulos serão identificados e descritos brevemente.  Os recursos *web* associados a cada módulo são pormenorizados na documentação individual de cada módulo dentro da especificação da *OpenAPI*.

|                 Identificação do Módulo                 |                     Descrição do Módulo                      |
| :-----------------------------------------------------: | :----------------------------------------------------------: |
| **M01: Autenticação de Utilizadores e Administradores** | Recursos *web* associados com as ações de autenticação. Inclui as seguintes funcionalidades do sistema: fazer *login* ou registar-se enquanto Visitante ou fazer *logout* enquanto Utilizador Autenticado e/ou Administradores. |
|                    **M02: Conteúdo**                    | Recursos *web* associados com as ações de criação, gestão e interação com conteúdo. Inclui as seguintes funcionalidades do sistema: criar publicações e comentários, editar publicações e comentários e apagar publicações e comentários; dar/retirar Gostos em publicações e reportar publicações e comentários. |
|                   **M03: Utilizador**                   | Recursos *web* associados com as ações de um Utilizador Autenticado. Inclui as seguintes funcionalidades do sistema: editar o próprio perfil, adicionar colega, apagar a própria conta, criar um grupo, ver as próprias notificações e marcá-las como vistas. |
|                   **M04: Moderador**                    | Recursos *web* associados com as ações de um Moderador no contexto de um Grupo. Inclui as seguintes funcionalidades do sistema: adicionar ou retirar membros ao Grupo, apagar conteúdo (publicações e comentários), ceder os seus privilégios de Moderador a outro Utilizador Autenticado que integre o Grupo e ver definições de Grupo. |
|             **M05: Pesquisa e *Timeline***              | Recursos *web* associados com a funcionalidade de pesquisa do sistema. Inclui as seguintes funcionalidades: pesquisar<sup>*</sup> (pessoas, publicações e grupos) e filtrar resultados (por certos parâmetros). |
|                 **M06: Administração **                 | Recursos *web* associados com a funcionalidade de Administração do sistema. Inclui as seguintes funcionalidades: apagar conteúdo (publicações e comentários), apagar contas (em contexto de banimento) e aplicar sanções a Utilizadores Autenticados. |
|               **M07:  Páginas Estáticas**               | Recursos *web* com conteúdo estático estão associados a este módulo: *FAQ* e *Sobre Nós*. |

<sup>*</sup> Isto inclui o ato de pesquisa (busca de termos) e a visualização dos resultados.

### Permissões

Definiremos nesta secção as permissões utilizadas nos módulos anteriores para estabelecer condições de acesso uniformes a recursos.

| **Identificador** | Designação             | Descrição                                         |
| ----------------- | ---------------------- | ------------------------------------------------- |
| **VIS**           | Visitante              | Utilizadores Não Autenticados.                    |
| **AUT**           | Utilizador Autenticado | Utilizadores Autenticados.                        |
| **MOD_DOC**       | Moderador Docente      | Administrador (Utilizador Docente) de um Grupo.   |
| **MOD_EST**       | Moderador Estudante    | Administrador (Utilizador Estudante) de um Grupo. |
| **ADM**           | Administrador          | Administrador do Sistema.                         |



### Especificações *OpenAPI*

```yaml
openapi: 3.0.0

info:
  version: '1.0'
  title: 'LBAW Rede Social Noodle'
  description: 'A7 para o Noodle'

servers:
  - url: http://lbaw2152.lbaw.fe.up.pt

externalDocs:
  description: Mais informação aqui.
  url: https://git.fe.up.pt/lbaw/lbaw2122/lbaw2152/-/wikis/home

tags:
  - name: 'M01: Autenticação de Utilizadores e Administradores'
  - name: 'M02: Conteúdo'
  - name: 'M03: Utilizador'
  - name: 'M04: Moderador'
  - name: 'M05: Pesquisa e Timeline'
  - name: 'M06: Administração'
  - name: 'M07: Páginas Estáticas'

paths:
  /login:
    get:
      operationId: R101
      summary: 'R101: Formulário de Login'
      description: 'Providenciar formulário de login. Acesso VIS'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'
      responses:
        '200':
          description: "OK, mostrar UI de Login"
    
    post:
      operationId: R102
      summary: 'R102: Ação de Login'
      description: 'Processa a submissão do formulário de login. Acesso: VIS'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                email:
                  type: string
                password:
                  type: string
              required:
                - email
                - password
      responses:
        '302':
          description: 'Redirecionar após processar credenciais de login.'
          headers:
            location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Autenticação bem-sucedida. Redirecionar ao feed personalizado.'
                  value: '/users/{id}'
                302Error:
                  description: 'Autenticação falhada. Redirecionar ao formulário de login.'
                  value: '/login'
    
  /logout:
    get:
      operationId: R103
      summary: 'R103: Ação de Logout'
      description: 'Terminar sessão do utilizador autenticado correntemente. Acesso: AUT'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'
      responses:
        '302':
          description: 'Redirecionar após processar logout.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Sucess:
                  description: 'Logout bem-sucedido. Redirecionar ao formulário de login.'
                  value: '/login'
    
  /register:
    get:
      operationId: R104
      summary: 'R104: Formulário de Registo'
      description: 'Providenciar novo formulário de registo de utilizador. Acesso: VIS'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'
      responses:
        '200':
          description: 'OK. Mostrar UI de Registo.'
      
    post:
      operationId: R105
      summary: 'R105: Ação de Registo'
      description: 'Processar a submissão de um formulário de registo. Acesso: VIS'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'

      requestBody:
        required: true
        content:
          application/x-ww-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                email:
                  type: string
                birthdate:
                  type: timestamp
                privacy:
                  type: string
                picture:
                  type: string
                  format: binary
              required:
                - email
                - password
                - nome
                - birthdate
                - privacy
      responses:
          '302':
            description: 'Redirecionamento após procesar a informação do novo utilizador.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Autenticação bem sucedida. Redirecionar ao escolha de tipo de utilizador.'
                    value: '/choose-path'
                  302Failure:
                    description: 'Autenticação falhada. Redirecionar ao formulário de login.'
                    value: '/login'

  /admin/login:
    get:
        operationId: R106
        summary: 'R106: Formulário de Login de Administradores'
        description: 'Providenciar formulário de login. Acesso: VIS'
        tags:
          - 'M01: Autenticação de Utilizadores e Administradores'
        responses:
          '200':
            description: "OK, mostrar UI de Login"
    
    post:
        operationId: R107
        summary: 'R107: Ação de Login de Administradores'
        description: 'Processa a submissão do formulário de login. Acesso: VIS'
        tags:
          - 'M01: Autenticação de Utilizadores e Administradores'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  email:
                    type: string
                  password:
                    type: string
                required:
                  - email
                  - password
        responses:
          '302':
            description: 'Redirecionar após processar credenciais de login.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Autenticação bem-sucedida. Redirecionar ao dashboard.'
                    value: '/admin/admin-users'
                  302Error:
                    description: 'Autenticação falhada. Redirecionar ao formulário de login.'
                    value: '/login'
    
  /admin/logout:
    get:
      operationId: R108
      summary: 'R108: Ação de Logout de Administradores'
      description: 'Terminar sessão do administrador correntemente. Acesso: ADM'
      tags:
        - 'M01: Autenticação de Utilizadores e Administradores'
      responses:
        '302':
          description: 'Redirecionar após processar logout.'
          headers:
            location:
              schema:
                type: string
              examples:
                302Sucess:
                  description: 'Logout bem-sucedido. Redirecionar ao formulário de login.'
                  value: '/login'

  /api/post/create:
    post:
      operationId: R201
      summary: 'R201: Criar Publicação'
      description: 'Criar publicação. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: conteudo
          schema:
            type: string
          required: true
        - in: path
          name: anexo
          schema:
            type: string
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Publicação feita.'
   
  /api/post/{id}:
    get:
      operationId: R202
      summary: 'R202: Formulário de Edição de Publicação'
      description: 'Providenciar novo formulário de registo de utilizador. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      responses:
        '200':
          description: 'OK. Mostrar Formulário de Edição de Publicação.'

    post:
      operationId: R203
      summary: 'R203: Editar Publicação'
      description: 'Editar publicação. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Publicação editada.'
    
    delete:
      operationId: R204
      summary: 'R204: Apagar Publicação'
      description: 'Apagar publicação. Acesso: AUT, MOD_DOC, MOD_EST, ADM'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Publicação apagada.'

  /api/comment/create:
    post:
      operationId: R205
      summary: 'R205: Criar Comentário'
      description: 'Criar comentário. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
        - in: path
          name: conteudo
          schema:
            type: string
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Comentário feito.'
  
  /api/comment/{id}:
    post:
      operationId: R206
      summary: 'R206: Editar Comentário'
      description: 'Editar comentário. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idcomentario
          schema:
            type: integer
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Comentário editado.'

    delete:
      operationId: R207
      summary: 'R207: Apagar Comentário'
      description: 'Apagar publicação. Acesso: AUT, MOD_DOC, MOD_EST, ADM'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
        - in: path
          name: idcomentario
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Comentário apagado.'
  
  /api/likes/create:
    post:
      operationId: R208
      summary: 'R208: Criar Gosto'
      description: 'Dar gosto. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Gosto feito.'
  
  /api/likes/{id}:
    delete:
      operationId: R209
      summary: 'R209: Apagar Gosto'
      description: 'Apagar gosto. Acesso: AUT'
      tags:
        - 'M02: Conteúdo'
      parameters:
        - in: path
          name: idpublicacao
          schema:
            type: integer
          required: true
        - in: path
          name: idutilizador
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Gosto apagado.'

  /choose-path:
    get:
      operationId: R301
      summary: 'R301: Formulário de Escolha de Tipo de Utilizador'
      description: 'Providenciar novo formulário de escolha de tipo de utilizador. Acesso: VIS'
      tags:
        - 'M03: Utilizador'
      responses:
        '200':
          description: 'OK. Mostrar UI de Escolha de tipo de utilizador.'
  
  /user/estudante:
    post:
      operationId: R302
      summary: 'R302: Criação de Utilizador Estudante'
      description: 'Processar a submissão de um formulário de obtenção de dados de um novo utilizador estudante. Acesso: VIS'
      tags:
        - 'M03: Utilizador'
      requestBody:
        required: true
        content:
          application/x-ww-form-urlencoded:
            schema:
              type: object
              properties:
                course:
                  type: string
                year:
                  type: int
                average:
                  type: int
              required:
                - course
                - year
                - average
      responses:
          '302':
            description: 'Redirecionamento após procesar a informação do novo utilizador estudante.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Autenticação bem sucedida. Redirecionar ao feed.'
                    value: '/genfeed'
                  302Failure:
                    description: 'Autenticação falhada. Redirecionar ao formulário de login.'
                    value: '/login'
  
  /user/docente:
    post:
      operationId: R303
      summary: 'R303: Criação de Utilizador Docente'
      description: 'Processar a submissão de um formulário de obtenção de dados de um novo utilizador docente. Acesso: VIS'
      tags:
        - 'M03: Utilizador'
      requestBody:
        required: true
        content:
          application/x-ww-form-urlencoded:
            schema:
              type: object
              properties:
                department:
                  type: string
                formation:
                  type: string
              required:
                - department
                - formation
      responses:
          '302':
            description: 'Redirecionamento após procesar a informação do novo utilizador docente.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Autenticação bem sucedida. Redirecionar ao feed geral.'
                    value: '/genfeed'
                  302Failure:
                    description: 'Autenticação falhada. Redirecionar ao formulário de login.'
                    value: '/login'
  
  /profile/{id}:
    get:
      operationId: R304
      summary: 'R304: Ver Perfil de Utilizador'
      description: 'Mostrar um perfil de um utilizador individual. Acesso: AUT, VIS, MOD_DOC, MOD_EST'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Ver UI de perfil de utilizador.'
  
  /config/{id}:
    get:
      operationId: R305
      summary: 'R305: Formulário de Editar Perfil e Definições de Utilizador'
      description: 'Providenciar formulário de edição de perfil e definições de utilizador. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Ver formulário de edição.'

    post:
      operationId: R306
      summary: 'R306: Editar Perfil'
      description: 'Editar o próprio perfil individual. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Alterações guardadas.'
  
  /users/{id}:
    delete:
      operationId: R307
      summary: 'R307: Apagar Conta'
      description: 'Apagar a própria conta. Acesso: AUT, ADM'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses: 
        '200':
          description: 'Conta apagada.'

  /api/groups/{userid}:
    post:
      operationId: R308
      summary: 'R308: Criar Grupo'
      description: 'Criar um grupo. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        - in: path
          name: nome
          schema:
            type: string
          required: true
        - in: path
          name: privacidade
          schema:
            type: string
          required: true
        - in: path
          name: tipo
          schema:
            type: string
          required: true
      responses:
        '200':
          description: 'Grupo criado.'
    
  /notifs:
    get:
      operationId: R309
      summary: 'R309: Ver Notificações'
      description: 'Ver notificações. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Mostrar notificações.'
  
    post:
      operationId: R310
      summary: 'R310: Marcar Notificação como Vista'
      description: 'Marcar notificações como vistas. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        - in: path
          name: idnotificacao
          schema:
            type: integer
          required: true
        - in: path
          name: vista
          schema:
            type: boolean
          required: true
      responses:
        '200':
          description: 'Notificação vista.'

  /users/friends/{id}:
    post:
      operationId: R311
      summary: 'R311: Adicionar colega'
      description: 'Adicionar colega. Acesso: AUT'
      tags:
        - 'M03: Utilizador'
      parameters:
        - in: path
          name: colega1
          schema:
            type: integer
          required: true
        - in: path
          name: colega2
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'Agora são colegas!'
    
    get:
      operationId: R312
      summary: 'R312: Ver lista de colegas'
      description: 'Utilizador deseja ver lista de colegas. Acesso: AUT'
      tags:
       - 'M03: Utilizador'
      parameters:
       - in: path
         name: user
         schema:
          type: integer
         required: true
      responses:
       '200':
         description: 'Ok. Aqui tem a lista de colegas'
    
    delete:
      operationId: R313
      summary: 'R313: Eliminar colega'
      description: 'Utilizador deseja terminar a sua relação com um determinado colega. Acesso: AUT'
      tags:
       - 'M03: Utilizador'
      parameters:
       - in: path
         name: user
         schema:
          type: integer
         required: true
      responses:
       '200':
         description: 'Ok. Colega eliminado'
    
  /group/{id}/{modid}:
    get:
      operationId: R401
      summary: 'R401: Adicionar membros ao Grupo'
      description: 'Moderador adiciona utilizadores ao grupo ao qual pertence. Acesso: MOD_EST, MOD_DOC'
      tags: 
          - 'M04: Moderador'
      parameters:
          - in: path
            name: idutilizador
            schema:
              type: integer
            required: true
          - in: path
            name: idGrupo
            schema:
              type: integer
            required: true
      responses: 
        '200':
          description: 'Ok. Membro adicionado ao Grupo'
    
    delete:
      operationId: R402
      summary: 'R402: Retirar membros ao Grupo'
      description: 'Moderador retira (apaga) utilizadores do grupo ao qual pertence. Acesso: MOD_EST, MOD_DOC'
      tags: 
          - 'M04: Moderador'
      parameters:
          - in: path
            name: idutilizador
            schema:
              type: integer
            required: true
          - in: path
            name: idGrupo
            schema:
              type: integer
            required: true
      responses: 
        '200':
          description: 'Ok. Membro retirado do Grupo'

    post:
      operationId: R403
      summary: 'R403: Ceder privilégio de moderador a outro membro do Grupo'
      description: 'Moderador pode decidir que quer abandonar o seu papel e ceder os seus privilégios a outro utilizador do Grupo. Acesso: MOD_EST, MOD_DOC'
      tags: 
          - 'M04: Moderador'
      parameters:
          - in: path
            name: idNovoModerador
            schema:
              type: integer
            required: true
          - in: path
            name: idGrupo
            schema:
              type: integer
            required: true
          - in: path
            name: idAntigoModerador
            schema:
              type: integer
            required: true
      responses: 
        '200':
          description: 'Ok. Novo Moderador adicionado ao Grupo'
    
  /genfeed:
    get:
      operationId: R501
      summary: 'R501: Timeline geral'
      description: 'Visualizar timeline geral. Acesso: AUT, VIS, MOD_EST, MOD_DOC, ADM'
      tags:
        - 'M05: Pesquisa e Timeline'
      responses:
        '200':
          description: 'OK. Mostrar Timeline geral.'
  
  /fyf:
    get:
      operationId: R502
      summary: 'R502: Timeline Personalizada'
      description: 'Visualizar timeline personalizada. Acesso: AUT'
      tags:
        - 'M05: Pesquisa e Timeline'
      responses:
        '200':
          description: 'OK. Mostrar Timeline Personalizada.'

  /api/search-usersnome-exact-search:
    get:
      operationId: R503
      summary: 'R503: Pesquisa de Utilizadores'
      description: 'Procura por utilzadores pelo nome e retorna os resultados como JSON. Acesso: VIS e AUT'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa exact match'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /api/search-usernome-full-text-search:
    get:
      operationId: R504
      summary: 'R504: Pesquisa de Utilizadores'
      description: 'Procura por utilizadores pelo nome e retorna os resultados como JSON. Acesso: VIS e AUT'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full text'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /api/search-post-full-text-search:
    get:
      operationId: R505
      summary: 'R505: Pesquisa de Posts'
      description: 'Procura por conteúdo - publicações - e retorna os resultados como JSON. Acesso: VIS e AUT'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full-text'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: integer
                    conteudo:
                      type: string
                    anexo:
                      type: string
                    idutilizador:
                      type: integer
              example:
                - id: 1
                  conteudo: "O céu é azul"
                  anexo: NULL
                  idutilizador: 1

  /api/search-usersnomepost-exact-search:
    get:
      operationId: R506
      summary: 'R506: Pesquisa de Posts de um dado Utilizador'
      description: 'Procura por posts pelo nome do seu autor e retorna os resultados como JSON. Acesso: VIS e AUT'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa exact match'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  conteudo: "O céu é azul"
                  anexo: NULL
                  idutilizador: 1

  /admin/admin-usersid-exact-search:
    get:
      operationId: R507
      summary: 'R507: Pesquisa de Utilizadores'
      description: 'Procura por utilzadores pelo id e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa exact match'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk
  
  /admin/admin-usersname-exact-search:
    get:
      operationId: R508
      summary: 'R508: Pesquisa de Utilizadores'
      description: 'Procura por utilzadores pelo nome e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa exact match'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk
  
  /admin/admin-usersemail-exact-search:
    get:
      operationId: R509
      summary: 'R509: Pesquisa de Utilizadores'
      description: 'Procura por utilzadores pelo email e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa exact match'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /admin/admin-usersid-full-text-search:
    get:
      operationId: R510
      summary: 'R510: Pesquisa de Utilizadores'
      description: 'Procura por utilizadores pelo id e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full text'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /admin/admin-usersname-full-text-search:
    get:
      operationId: R511
      summary: 'R511: Pesquisa de Utilizadores'
      description: 'Procura por utilizadores pelo nome e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full text'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /admin/admin-usersemail-full-text-search:
    get:
      operationId: R512
      summary: 'R512: Pesquisa de Utilizadores'
      description: 'Procura por utilizadores pelo email e retorna os resultados como JSON. Acesso: ADM'

      tags:
        - 'M05: Pesquisa e Timeline'
      
      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full text'
          schema:
            type: string
          required: true
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    email:
                      type: string
                example:
                  id: 1
                  nome: Brianna Cortez
                  email: aenean@protonmail.couk

  /api/groups:
    get:
      operationId: R513
      summary: 'R513: Pesquisa de Grupo'
      description: 'Procura por grupo - pelo nome deste - e retorna os resultados como JSON. Acesso: AUT'

      tags:
        - 'M05: Pesquisa e Timeline'

      parameters:
        - in: query
          name: query
          description: 'String a usar para pesquisa full-text'
          schema:
            type: string
          required: true
        - in: query
          name: group-filter
          description: 'Filtro da pesquisa (para grupos - pelo título)'
          schema:
            type: boolean
          required: false
        - in: query
          name: post-date-filter
          description: 'Filtro por data para publicações.'
          schema:
            type: string
          required: false
      responses:
        '200':
          description: Sucesso
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    id:
                      type: string
                    nome:
                      type: string
                    tipo:
                      type: string
                example:
                  id: 1
                  nome: Projeto de LBAW | 2152
                  tipo: work

  /admin/admin-users:
    get:
      operationId: R601
      summary: 'R601: Visualização de Todos os Utilizadores'
      description: 'Visualizar uma lista de todos os utilizadores existentes na plataforma. Acesso: ADM'
      tags:
        - 'M06: Administração'
      responses:
        '200':
          description: 'OK. Ver lista de todos os utilizadores'
          
  /admin/profile/{id}:
    get:
      operationId: R602
      summary: 'R602: Ver Perfil de Utilizador'
      description: 'Mostrar um perfil de um utilizador individual. Acesso: ADMN'
      tags:
        - 'M06: Administração'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
          description: 'OK. Ver UI de perfil de utilizador.'

  /about:
    get:
      operationId: R701
      summary: 'R701: Sobre Nós'
      description: 'Visualizar página estática "Sobre Nós". Acesso: AUT, VIS, MOD_DOC, MOD_EST, ADM'
      tags:
        - 'M07: Páginas Estáticas'
      responses:
        '200':
          description: 'OK'
  
  /faq:
    get:
      operationId: R702
      summary: 'R702: FAQ'
      description: 'Visualizar página estática "FAQ". Acesso: AUT, VIS, MOD_DOC, MOD_EST, ADM'
      tags:
        - 'M07: Páginas Estáticas'
      responses:
        '200':
          description: 'OK. Mostrar FAQ.'

```

## A8: Protótipo Vertical

Esta secção debruçar-se-á sobre a implementação feita para a entrega da componente **EAP**, da qual faz parte um protótipo vertical com um conjunto de funcionalidades esperadas a serem implementadas.

### Funcionalidades Implementadas

#### Histórias de Utilizador Implementadas

No sistema da plataforma *Noodle*, as histórias de utilizador implementadas estão descritas na seguinte tabela.

| Identificador | Nome                            | Prioridade | Descrição                                                    |
| ------------- | ------------------------------- | ---------- | ------------------------------------------------------------ |
| US01          | Iniciar Sessão                               | Alta       | Enquanto *Visitante*, quero autenticar-me no sistema para aceder a informação privilegiada. |
| US02          | Registar                                     | Alta       | Enquanto *Visitante*, quero registar-me no sistema para permitir autenticação no sistema.   |
| US03          | Ver Página Inicial                           | Alta       | Enquanto *Visitante*, quero poder presenciar um *timeline* de publicações populares que me dêem uma imagem do que é ser um Utilizador Autenticado. |
| US04          | Ver FAQ                                      | Alta       | Enquanto *Visitante*, quero poder aceder à pagina de FAQs que possam esclarecer dúvidas minhas sobre a equipa e funcionamento do site. |
| US05          | Ver Sobre Nós                                | Alta       | Enquanto *Visitante*, desejo poder aceder à página de informação geral sobre a plataforma *Noodle* e os seus criadores. |
| US11          | Terminar sessão                              | Alta       | Enquanto *Utilizador Autenticado*, desejo poder terminar a minha sessão no site para impedir acessos inautorizados. |
| US12          | Ver Página Inicial                           | Alta       | Como *Utilizador Autenticado*, desejo aceder à página inicial, onde se encontra o meu *timeline.* |
| US13          | Ver Aba de Notificações                      | Alta       | Enquanto *Utilizador Autenticado* quero poder ver as notificações para não perder acesso a informação que desejam que chegue a mim. |
| US14          | Ver Definições                               | Alta       | Como *Utilizador Autenticado*, desejo ver a página de definições para proceder a quaisquer alterações necessárias para uma melhor experiência. |
| US15          | Apagar Conta                                 | Alta       | Como *Utilizador Autenticado*, desejo poder agendar a remoção da minha conta caso não pretenda usá-la mais. |
| US16          | Ver Perfil                                   | Alta       | Como *Utilizador Autenticado*, desejo ver o meu próprio perfil, para ver como outros *Utilizadores Autenticados* o vêem. |
| US17          | Editar Perfil                                | Alta       | Como *Utilizador Autenticado*, desejo poder editar o conteúdo do meu perfil a meu gosto. |
| US18          | Fazer Publicação                             | Alta       | Como *Utilizador Autenticado*, desejo poder partilhar informação a partir de publicações próprias. |
| US110         | Editar Publicação                            | Alta       | Como *Utilizador Autenticado*, desejo poder editar uma publicação para corrigir erros que possam comprometer a informação que quero transmitir. |
| US111         | Apagar publicação                            | Alta       | Como *Utilizador Autenticado*, desejo poder apagar publicações feitas por mim. |


#### Recursos *Web* Implementados

No sistema da plataforma *Noodle*, os recursos web implementados estão descritos nas seguintes tabelas.

##### Módulo M01:  Autenticação de Utilizadores e Administradores

| Referência do Recurso *Web* | URL |
| ----------------- | ------------------------------------------------- |
| R101: Formulário de *Login* | GET /login |
| R102: Ação de *Login* | POST /login |
| R103: Ação de *Logout* | GET /logout |
| R104: Formulário de Registo | GET /register |
| R105: Ação de Registo | POST /register |
| R106: Formulário de *Login* de Administradores | GET /admin/login |
| R107: Ação de *Login* de Administradores | POST /admin/login |
| R108: Ação de *Logout* de Administradores | GET /admin/logout |

##### Módulo M02: Conteúdo

| Referência do Recurso *Web*              | URL                   |
| ---------------------------------------- | --------------------- |
| R201: Criar Publicação                   | POST /api/post/create |
| R202: Formulário de Edição de Publicação | GET /api/post/{id}    |
| R203: Editar Publicação                  | POST /api/post/{id}   |
| R204: Apagar Publicação                  | DELETE /api/post/{id} |

##### Módulo M03:  Utilizador

| Referência do Recurso *Web*                                  | URL                  |
| ------------------------------------------------------------ | -------------------- |
| R301: Formulário de Escolha de Tipo de Utilizador            | GET /choose-path     |
| R302: Criação de Utilizador Estudante                        | POST /user/estudante |
| R303: Criação de Utilizador Docente                          | POST /user/docente   |
| R304: Ver Perfil de Utilizador                               | GET /profile/{id}    |
| R305: Formulário de Editar Perfil e Definições de Utilizador | GET /config/{id}     |
| R306: Editar Perfil                                          | POST /config/{id}    |
| R307: Apagar Conta                                           | DELETE /users/{id}   |

##### Módulo M05: Pesquisa e *Timeline*

| Referência do Recurso *Web*    | URL          |
| ------------------------------ | ------------ |
| R501: *Timeline* Geral         | GET /genfeed |
| R502: *Timeline* Personalizada | GET /fyf     |

##### Módulo M06:  Administração

| Referência do Recurso *Web*                 | URL                     |
| ------------------------------------------- | ----------------------- |
| R601: Visualização de Todos os Utilizadores | GET /admin/admin-users  |
| R602: Ver Perfil de Utilizador              | GET /admin/profile/{id} |

##### Módulo M07:  Páginas Estáticas

| Referência do Recurso *Web* | URL        |
| --------------------------- | ---------- |
| R701: Sobre Nós             | GET /about |
| R702: FAQ                   | GET /faq   |

### Protótipo

O código do protótipo está disponível em https://git.fe.up.pt/lbaw/lbaw2122/lbaw2152/-/tree/main.

O protótipo vertical está *online* em https://lbaw2152.lbaw.fe.up.pt.

#### ***Credenciais***

-  *Administrador*
  - Email: admin@gmail.com
  - Password: admin

   - *Utilizador Docente*
        - Email: test@gmail.com
        - Password: password
   - *Utilizador Estudante*
        - Email: test2@gmail.com
        - Password: password10



## **Autoavaliação**

Link para a spreadsheet: https://docs.google.com/spreadsheets/d/16L6nAgDa9fuEBVlfhYZxvXK0VGgl8F_wYjcHe1pPU3g/edit#gid=1916533523
