# Building DynGUA-PD

DynGUA-PD is packaged as a native FreeBSD/pfSense port. It has no compilation
step; the Ports framework validates, stages, and packages the runtime files.

## Build Environment

Use an amd64 FreeBSD or pfSense build host that matches the target pfSense
release. The 0.8.0 Beta RC1 package targets pfSense CE 2.9.0 and FreeBSD 16.

Required components:

- A ports tree matching the target pfSense release
- FreeBSD `make` and `pkg`
- PHP from the target pfSense environment for syntax validation
- `portlint` for source checks
- `socat`; the port also declares it as a runtime dependency
- Optional `gettext-tools` for validating translation catalogs with `msgfmt`

Do not build a release package from a production firewall. Use an isolated
build host or a disposable pfSense test system.

## Source Layout

The port must be located at the path matching its category and origin:

```text
/usr/ports/net/pfSense-pkg-dgpd
```

Clone or copy the `rc-beta` branch into that directory:

```sh
cd /usr/ports/net
git clone --branch rc-beta --single-branch \
	https://github.com/gebra-de/pfSense-Dyn-GUA-Delegation.git pfSense-pkg-dgpd
cd pfSense-pkg-dgpd
```

## Validate the Sources

```sh
portlint -AC

php -l files/conf/kea6-scripts/kea-pd-allocator.php
php -l files/usr/local/libexec/dgpd_worker.php
php -l files/usr/local/pkg/dgpd.inc
php -l files/usr/local/pkg/dgpd_kea.inc
```

If `msgfmt` is installed, validate the German translation catalog:

```sh
msgfmt --check --check-format -o /dev/null translations/de_DE.po
```

The translation catalogs are contribution sources for pfSense's global
gettext catalog. The package does not install a private `pfSense.mo` file.

## Build the Package

Start from a clean work directory and let the Ports framework stage and check
the complete packing list before creating the package:

```sh
make clean
make stage
make check-plist
make package
```

The resulting package is written below `work/pkg/`:

```text
work/pkg/pfSense-pkg-dgpd-0.8.0.r1.pkg
```

Minimal pfSense test installations may not contain FreeBSD source headers. If
the Ports framework cannot determine `OSVERSION`, provide the value reported by
the running kernel:

```sh
OSVERSION="$(sysctl -n kern.osreldate)"
export OSVERSION

make clean
make stage
make check-plist
make package
```

This override is only appropriate when the build host matches the target
system. It does not make packages from a mismatched ports tree compatible.

## Inspect the Package

```sh
pkg info -F work/pkg/pfSense-pkg-dgpd-0.8.0.r1.pkg
tar -tf work/pkg/pfSense-pkg-dgpd-0.8.0.r1.pkg
sha256 work/pkg/pfSense-pkg-dgpd-0.8.0.r1.pkg
```

Confirm that the manifest reports:

- Name: `pfSense-pkg-dgpd`
- Version: `0.8.0.r1`
- Origin: `net/pfSense-pkg-dgpd`
- License: `APACHE20`
- Maintainer: `projects@gbrands.de`

## Test Installation

Back up the pfSense configuration before installing a pre-release package.
Install only on an appropriate test system:

```sh
pkg add -f work/pkg/pfSense-pkg-dgpd-0.8.0.r1.pkg
pkg check -s pfSense-pkg-dgpd
pkg info pfSense-pkg-dgpd
```

Open **Services > Dynamic GUA-PD** and verify prerequisites, existing native
DHCPv6 configuration, assignments, Kea runtime state, and downstream routing
before exercising state-changing actions.

## Release Artifact

The binary `.pkg` file is distributed as a release asset rather than committed
to the source branch. Publish its SHA256 checksum with the asset and create the
release from the signed `beta-rc1` tag.
