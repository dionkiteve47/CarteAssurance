import React from 'react';
import ReactDOM from 'react-dom/client'; // Import createRoot from react-dom/client
import { registerReactControllerComponents } from '@symfony/ux-react';
import './bootstrap.js';
import './styles/app.css';


registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));

// Use ReactDOM.createRoot instead of ReactDOM.render



