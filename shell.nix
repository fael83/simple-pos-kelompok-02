{ pkgs ? import <nixpkgs> {} }:
let
  php = pkgs.php84;
in pkgs.mkShell {
  buildInputs = [ php pkgs.php84Packages.composer ];
}
